<?php
namespace App\Shell\Helper;

use Cake\Console\Helper;
use Cake\Console\ConsoleIo;
use Cake\Log\Log;
use Cake\Core\Configure;

class ApiVtexHelper extends Helper
{
	private $hostTracking;
	public function __construct(ConsoleIo $io, array $config = [])
    {
		parent::__construct($io, $config);
		$this->hostTracking = Configure::read('Iflow.host_tracking');
	}

	public function output($args): void
	{
	}

	public function createCarrier($store)
	{
		Log::write('info', 'Creando transporte');
		$carrierId = $store['id'];
		$url = "https://{$store['account_name']}.{$store['environment']}.com.br/api/logistics/pvt/configuration/carriers";
		$data = [
			'id' => $carrierId,
			'slaType' => 'iFLow',
			'name' => 'iFLow',
			'scheduledDelivery' => false,
			'maxRangeDelivery' => 0,
			'dayOfWeekForDelivery' => null,
			'dayOfWeekBlockeds' => [],
			'freightValue' => [],
			'factorCubicWeight' => null,
			'freightTableProcessStatus' => 1,
			'freightTableValueError' => null,
			'modals' => [],
			'onlyItemsWithDefinedModal' => false,
			'deliveryOnWeekends' => false,
			'carrierSchedule' => [],
			'maxDimension' => [
				'weight' => 0,
				'height' => 0,
				'width' => 0,
				'length' => 0,
				'maxSumDimension' => 0
			],
			'exclusiveToDeliveryPoints' => false,
			'minimunCubicWeight' => 0,
			'isPolygon' => false,
			'numberOfItemsPerShipment' => null
		];

		$rest = $this->getRest($store);
		return $rest->post($url, $data);
	}

	public function createWarehouse($store)
	{
		Log::write('info', 'Creando almacen');
		$carrierId = $store['id'];
		$url = "https://{$store['account_name']}.{$store['environment']}.com.br/api/logistics/pvt/configuration/warehouses";
		$data = [
			'id' => $carrierId,
			'name' => 'iFlow',
			'warehouseDocks' => [
				[
					'dockId' => $carrierId,
					'name' => 'Iflow',
					'time' => '00:00:00',
					'cost' => '0.00',
					'translateDays' => 'dias',
					'costToDisplay' => '5,00'
				]
			]
		];

		$rest = $this->getRest($store);
		return $rest->post($url, $data);
	}

	public function createDocks($store)
	{
		Log::write('info', 'Creando muelle');
		$carrierId = $store['id'];
		$url = "https://{$store['account_name']}.{$store['environment']}.com.br/api/logistics/pvt/configuration/docks";
		$data = [
			'id' => $carrierId,
			'name' => 'iFlow',
			'priority' => 10,
			'dockTimeFake' => '00:00:00',
			'timeFakeOverhead' => '00:00:00',
			'salesChannels' => [
				'1'
			],
			'salesChannel' => null,
			'freightTableIds' => [
				$carrierId
			],
			'wmsEndPoint' => '',
			'PickupStoreInfo' => [
				'isPickupStore' => false,
				'storeId' => null,
				'friendlyName' => null,
				'address' => [
					'postalCode' => $store['postal_code'],
					'country' => [
						'acronym' => 'ARG',
						'name' => 'Argentina'
					],
					'city' => $store['city'],
					'state' => $store['state'],
					'neighborhood' => '',
					'street' => $store['street'],
					'number' => $store['street_number'],
					'complement' => '',
					'coordinates' => null
				],
				'additionalInfo' => null,
				'dockId' => null
			],
			'address' => [
				'postalCode' => $store['postal_code'],
				'country' => [
					'acronym' => 'ARG',
					'name' => 'Argentina'
				],
				'city' => $store['city'],
				'state' => $store['state'],
				'neighborhood' => '',
				'street' => $store['street'],
				'number' => $store['street_number'],
				'complement' => '',
				'coordinates' => [
					[

					]
				]
			]
		];

		$rest = $this->getRest($store);
		return $rest->post($url, $data);
	}

	public function updatePrices($store, array $quotations)
	{
		$rest = $this->getRest($store);

		$carrierId = $store['id'];
		$url = "https://{$store['account_name']}.{$store['environment']}.com.br/api/logistics/pvt/configuration/freights/$carrierId/values/update";

		// Tengo que eliminar esta cotización (se genera sola) porque sino me devuelve siempre el
		// valor 5 para cualquier código postal
		$data = [
			[
				'level' => null,
				'zipCodeOriginStart' => null,
				'zipCodeOriginEnd' => null,
				'zipCodeStart' => "0",
				'zipCodeEnd' => "99999999",
				'zipCodeDestinationStart' => null,
				'zipCodeDestinationEnd' => null,
				'weightStart' => 0.0,
				'weightEnd' => 10000.0,
				'absoluteMoneyCost' => 5.0,
				'pricePercent' => 0.0,
				'pricePercentByWeight' => 0.0,
				'maxVolume' => 1000000000.0000,
				'timeCost' => "3.00:00:00",
				'country' => "ARG",
				'operationType' => 3,
				'restrictedFreights' => [],
				'polygonOrigin' => null,
				'polygonDestinationName' => null,
				'polygon' => "",
				'minimumValueInsurance' => 0.0
			]
		];
		Log::write('info', 'Elimino tarifa por defecto');
		$rest->post($url, $data);

		$data_chunk = array_chunk($quotations, 100);
		$i = 0;
		$count = count($data_chunk);
		foreach ($data_chunk as $rows) {
			$batchs = [];
			foreach ($rows as $dataIflow) {
				$batchs[] = [
					"country" => "ARG",
					"absoluteMoneyCost" => $dataIflow['AbsoluteMoneyCost'],
					"maxVolume" => 1000000000,
					"operationType" => 2,
					"pricePercent" => 0,
					"pricePercentByWeight" => 0,
					"timeCost" => $dataIflow['TimeCost'],
					"weightEnd" => $dataIflow['WeightEnd'],
					"weightStart" => $dataIflow['WeightStart'],
					"zipCodeEnd" => $dataIflow['ZipCodeEnd'],
					"zipCodeStart" => $dataIflow['ZipCodeStart'],
					"polygon" => ""
				];	
			}
			$i++;
			Log::write('info', "Actualizando tarifa $i de $count");
			$rest->post($url, $batchs);
		}

		return $rest;
	}

	public function cleanFreights($store, array $quotations)
	{
		Log::write('info', 'Elimino todas las tarifas');

		$rest = $this->getRest($store);

		$carrierId = $store['id'];
		$url = "https://{$store['account_name']}.{$store['environment']}.com.br/api/logistics/pvt/configuration/freights/$carrierId/values/update";

		$data_chunk = array_chunk($quotations, 100);
		$i = 0;
		$count = count($data_chunk);
		foreach ($data_chunk as $rows) {
			$batchs = [];
			foreach ($rows as $dataIflow) {
				$batchs[] = [
					"country" => "ARG",
					"absoluteMoneyCost" => $dataIflow['AbsoluteMoneyCost'],
					"maxVolume" => 1000000000,
					"operationType" => 3,
					"pricePercent" => 0,
					"pricePercentByWeight" => 0,
					"timeCost" => $dataIflow['TimeCost'],
					"weightEnd" => $dataIflow['WeightEnd'],
					"weightStart" => $dataIflow['WeightStart'],
					"zipCodeEnd" => $dataIflow['ZipCodeEnd'],
					"zipCodeStart" => $dataIflow['ZipCodeStart'],
					"polygon" => ""
				];	
			}
			$i++;
			Log::write('info', "Fila $i de $count");
			$rest->post($url, $batchs);
		}

		return $rest;
	}

	public function listOrders($store)
	{
		$rest = $this->getRest($store);

		$orders = $this->getOrders($rest, $store, 1);

		return $orders;
	}

	public function getOrder($store, $order_id)
	{
		$rest = $this->getRest($store);
		$rest->acceptJson = true;
		$url = "https://{$store['account_name']}.{$store['environment']}.com.br/api/oms/pvt/orders/$order_id";
		$body = $rest->get($url);

		if (isset($body['error'])) {
			return false;
		}

		return $body;
	}

	public function sendTracking($store, $order_iflow, $order_vtex)
	{
		$invoiceNumber = $order_vtex['packageAttachment']['packages'][0]['invoiceNumber'];
		$rest = $this->getRest($store);
		$rest->acceptJson = true;
		$url = "https://{$store['account_name']}.{$store['environment']}.com.br/api/oms/pvt/orders/{$order_vtex['orderId']}/invoice/{$invoiceNumber}";
		$urlTracking = $this->hostTracking . '/#/tracking/code/' . $order_iflow['tracking_id'];

		$data = [
			'trackingNumber' => $order_iflow['tracking_id'],
			'trackingUrl' => $urlTracking,
			'courier' => 'iFlow'
		];
		$body = $rest->patch($url, $data);

		if (isset($body['error'])) {
			Log::write('error', "Error al informar el seguimiento.\n" . json_encode($body));
			debug($body);
		}

		return $body && isset($body['receipt']) ? $body : false;
	}

	private function getRest($store)
	{
		$rest = $this->_io->helper('Rest');
		$rest->logTitle = 'REQUEST VTEX';
		$rest->setConfig('header', [
			'X-VTEX-API-AppKey' => $store['appkey'],
			'X-VTEX-API-AppToken' => $store['apptoken']
		]);
		$rest->reset();

		return $rest;
	}

	private function getOrders($rest, $store, $page, &$list = [])
	{
		$url = "https://{$store['account_name']}.{$store['environment']}.com.br/api/oms/pvt/orders?";
		//$url = "http://localhost:8000?";  // para hacer test
		$url .= http_build_query([
			'f_creationDate' => 'creationDate:[' . date('Y-m-d', strtotime('-1 month')) . 'T03:00:00.000Z TO ' . date('Y-m-d', strtotime('+1 day')) . 'T02:59:59.999Z]',
			'orderBy' => 'creationDate,desc',
			'page' => $page,
			'per_page' => 20,
			//'f_status' => 'ready-for-handling' // Listo para enviar
		]);
		$rest->acceptJson = true;
		$body = $rest->get($url);

		if ($body['paging']['pages'] > $page) {
			$list = array_merge($list, $body['list']);
			return $this->getOrders($rest, $store, ++$page, $list);
		}

		return array_merge($list, $body['list']);
	}
}