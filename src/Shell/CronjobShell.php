<?php
namespace App\Shell;

use Cake\Console\Shell;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;
use Cake\Log\Engine\FileLog;
use Cake\Log\Log;

class CronjobShell extends Shell
{
	private $apiIflow;
	private $apiVtex;
	private $Orders;
	private $Stores;

	public function initialize(): void
	{
		Log::setConfig('info', [
			'className' => FileLog::class,
			'path' => LOGS,
			'levels' => ['info', 'error'],
			'file' => 'cronjob',
		]);

		$this->apiIflow = $this->_io->helper('ApiIflow');
		$this->apiIflow->setConfig('rest', Configure::read('iflow'));
		$this->apiVtex = $this->_io->helper('ApiVtex');

		$this->Orders = TableRegistry::get('Orders');
		$this->Stores = TableRegistry::get('Stores');
	}

	public function main()
	{
		$this->out('Commands:');
		$this->out('sync - Crea/Actualiza el transportista, almacen, muelle y el precio de los fletes.');
		$this->out('clear_freight - Elimina todos los precios de fletes.');
		$this->out('tracking - Informa a vtex el código de seguimiento para todas las cuentas.');
		$this->out('tracking_by_store <store_id> - Informa a vtex el código de seguimiento para una tienda en particular.');
		$this->out('tracking_by_order <order_vtex_id> <account_name> - Informa a vtex el código de seguimiento para un pedido.');
	}

	public function sync()
	{
		$stores = $this->getStores();
		if (!$stores) {
			return false;
		}

		Log::write('info', 'Iniciando sincronización con vtex');
		foreach ($stores as $store) {
			$quotations = $this->apiIflow->getQuotations($store);

			$this->apiVtex->createCarrier($store);
			$this->apiVtex->createDocks($store);
			$this->apiVtex->createWarehouse($store);
			if ($quotations) {
				$this->apiVtex->cleanFreights($store, $quotations);
				$this->updatePrices($store, $quotations);
			} else {
				Log::write('error', 'No tengo tarifas para enviar a vtex store id #' . $store['id']);
			}
		}

		return true;
	}

	public function clearFreight()
	{
		$stores = $this->getStores();
		if (!$stores) {
			return false;
		}
		$quotations = $this->apiIflow->getQuotations();

		Log::write('info', 'Iniciando la limpieza de los precios de fletes');
		foreach ($stores as $store) {
			$this->apiVtex->cleanFreights($store, $quotations);
		}

		return true;
	}

	public function tracking()
	{
        Log::write('info', 'Init command [tracking]');

		$stores = $this->getStores();
		if (!$stores) {
            Log::write('error', 'Error al obtener los stores. No hay stores registrados.');
			return false;
		}

		$completedOrders = $this->getCompletedOrders();

		foreach ($stores as $store) {
			$this->processTracking($store, $completedOrders);
		}

		return true;
	}

	public function trackingByStore($store_id)
	{
		$store = $this->Stores->find()->where(['id' => $store_id])->first();
		if (!$store) {
			Log::write('info', 'No existe la tienda id #' . $store_id);
			return false;
		}
		$completed_orders = $this->getCompletedOrders();
		$this->processTracking($store, $completed_orders);
		return true;
	}

	public function trackingByOrder($order_id, $account_name)
	{
		$orderExists = $this->Orders->find()->where(['order_id_vtex' => $order_id])->count() > 0;
		if ($orderExists) {
			Log::write('info', "El pedido #{$order_id} ya ha sido informado.");
			return false;
		}

		$store = $this->Stores->find()->where(['account_name' => $account_name])->first();
		if (!$store) {
			Log::write('info', "No existe la tienda $account_name");
			return false;
		}

		$orderVtex = $this->apiVtex->getOrder($store, $order_id);
		if (!$orderVtex) {
			Log::write('info', 'Order not found');
			return false;
		}

		$this->sendTracking($store, $orderVtex);
		return true;
	}

    /**
     * Obtiene las tiendas disponibles.
     *
     * @return array Arreglo de tiendas disponibles. Si no hay tiendas cargadas, devuelve un arreglo vacío.
     */
	private function getStores():array
	{
		$stores = $this->Stores->find()->toArray();

		if (empty($stores)) {
			Log::write('error', 'No hay tiendas cargadas');
			return [];
		}

		return $stores;
	}

	private function updatePrices($store, $quotations)
	{
		$rest = $this->apiVtex->updatePrices($store, $quotations);

		if (!$rest->successful) {
			$packages = $rest->getErrors();
			$count = count($packages);
			Log::write('error', "Ocurrieron {$count} errores");
			Log::write('info', 'Reintentando requests');
			$rest->reset();
			$rest->logTitle = 'REQUEST VTEX';
			$rest->setDelay(200000);
			foreach ($packages as $package) {
				$rest->request($package['method'], $package['url'], $package['data']);
			}
			$count = count($rest->getErrors());
			if ($count > 0) {
				Log::write('error', "Ocurrieron {$count} errores");
			}
		}
	}

    /**
     * Procesa el seguimiento de pedidos para una tienda específica.
     *
     * @param array $store             Arreglo que contiene información de la tienda.
     * @param array $completed_orders  Arreglo de IDs de órdenes en bbdd.
     *
     * @return void
     */
	private function processTracking($store, $completed_orders)
	{
		$orders = $this->apiVtex->listOrders($store);

		foreach ($orders as $order) {
            // Verifica si la orden ya ha sido registrada.
			if (in_array($order['orderId'], $completed_orders)) {
                // Si la orden ya ha sido registrada en bbdd, registra un mensaje informativo y continúa con la siguiente orden.
				Log::write('info', "El pedido #{$order['orderId']} ya ha sido informado.");
				continue;
			}

			$orderVtex = $this->apiVtex->getOrder($store, $order['orderId']);

            Log::write('info', 'Order vtex : ' . json_encode($orderVtex, true));
            if (! $orderVtex) {
                return;
            }

			$this->sendTracking($store, $orderVtex);
		}
	}

    /**
     * Envía el seguimiento de una orden a través de la API iFlow y VTEX y guarda la información relacionada en la base de datos.
     *
     * @param array $store      Arreglo que contiene información de la tienda.
     * @param array $order_vtex Arreglo que contiene los detalles de la orden provenientes de VTEX.
     *
     * @return void
     */
	private function sendTracking($store, $order_vtex)
	{
		if (!isset($order_vtex['packageAttachment']['packages'][0]['invoiceNumber'])) {
			Log::write('info', "La orden #{$order_vtex['orderId']} no tiene factura");
			return;
		}

		$resultOrderIflow = $this->apiIflow->createOrder($store, $order_vtex);
		if (!$resultOrderIflow) {
			return;
		}

		$resultTracking = $this->apiVtex->sendTracking($store, $resultOrderIflow, $order_vtex);
		if ($resultTracking) {
			Log::write('info', 'Guardando orden #' . $order_vtex['orderId']);
			$entity = $this->Orders->newEmptyEntity();
			$entity = $this->Orders->patchEntity($entity, [
				'order_id_vtex' => $order_vtex['orderId'],
				'tracking_id' => $resultOrderIflow['tracking_id'],
				'invoice_number' => $order_vtex['packageAttachment']['packages'][0]['invoiceNumber'],
				'response_order_iflow' => json_encode($resultOrderIflow),
				'response_tracking_vtex' => json_encode($resultTracking),
			]);
			$this->Orders->save($entity);
		}
	}

    /**
     * Obtiene los IDs de las órdenes.
     *
     * @return array Arreglo de IDs de órdenes.
     */
	private function getCompletedOrders()
	{
		$orders = $this->Orders->find()->select('order_id_vtex')->toArray();
		$orders = array_map(function($order) {
            return $order['order_id_vtex'];
        }, $orders);

		return $orders;
	}
}
