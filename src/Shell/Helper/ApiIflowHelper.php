<?php
namespace App\Shell\Helper;

use Cake\Console\Helper;
use Cake\Console\ConsoleIo;
use Cake\Log\Log;

class ApiIflowHelper extends Helper
{
	private $cache_token = '';

	public function output($args): void
	{
	}

	public function getQuotations($store): array
	{
		Log::write('info', 'Consultando los datos de iFlow');
		$rest = $this->_io->helper('Rest');
		$rest->reset();
		$rest->logTitle = 'REQUEST IFLOW';
		$rest->acceptJson = true;
		$url = $this->getConfig('rest.url_quotations');
		$response = $rest->post($url, [
			'ApiClient' => $store['user_api_iflow'],
			'clave' => $store['pass_api_iflow'],
		]);

        // Log::write('info', json_encode($response, true));

		if (!$response) {
			return [];
		}

		return $response;
	}

	public function getToken($store)
	{
		if ($this->cache_token) {
			return $this->cache_token;
		}
		$url = $this->getConfig('rest.host_api') . '/api/login';
		$rest = $this->_io->helper('Rest');
		$rest->reset();
		$response = $rest->post($url, [
			'_username' => $store['user_api_iflow'],
			'_password' => $store['pass_api_iflow']
		]);

		if ($response['success'] === false) {
            Log::write('error', 'Error al loguearse a la api de iflow ('.$url.') con las credenciales ('.json_encode(['username' => $store['user_api_iflow'], 'password' => $store['pass_api_iflow']], true).'), respuesta de la api : '.json_encode($response, true));
			return false;
		}
		$this->cache_token = $response['token'];
		return $response['token'];
	}

    /**
     * Crea una orden en el sistema iFlow y devuelve los resultados.
     *
     * @param array $store  Arreglo que contiene información de la tienda.
     * @param array $order  Arreglo que contiene los detalles de la orden a ser creada.
     *
     * @return array|bool   Retorna los resultados de la creación de la orden en iFlow si fue exitosa, de lo contrario, retorna false.
     */
	public function createOrder($store, $order)
	{
		Log::write('info', 'Creando orden en iFlow');
		$url = $this->getConfig('rest.host_api') . '/api/order/create';
		$rest = $this->_io->helper('Rest');
		$rest->reset();
		$rest->logTitle = 'REQUEST IFLOW';
		$rest->acceptJson = true;
		$token = $this->getToken($store);

		if (!$token) {
			return false;
		}

		$dimension = ['width' => 0, 'height' => 0, 'length' => 0, 'weight' => 0];
		foreach ($order['items'] as $item) {
			$dimension['width'] += $item['additionalInfo']['dimension']['width'];
			$dimension['height'] += $item['additionalInfo']['dimension']['height'];
			$dimension['length'] += $item['additionalInfo']['dimension']['length'];
			$dimension['weight'] += $item['additionalInfo']['dimension']['weight'];
		}

		$data = [
			'order_id' => $order['orderId'],
			'delivery_shift' => 1,  // Túrno para operación logistica (valores esperados 1: 9a14hs, 2: 14a20hs, 3: 9a20hs)
			'delivery_mode' => 1,   // Modo de envío (producción: lo define comercial iFlow con el cliente, Testing: [valor: 1] )
			'shipments' => [
				[
					'items_value' => $this->getNumber($order['totals'][0]['value']),     // Valor de los ítems expresado en pesos ARS
					'shipping_cost' => $this->getNumber($order['totals'][2]['value']),   // Valor calculado de costo de envío expresado en pesos ARS
					'delivery_shift' => 1,  // esta opción no esta documentada pero sale así en el ejemplo
					'width'  => $dimension['width'],
					'height' => $dimension['height'],
					'length' => $dimension['length'],
					'weight' => $dimension['weight'],
					'items' => []
				]
			],
			'receiver' => [
				'first_name' => $order['clientProfileData']['firstName'],
				'last_name' => $order['clientProfileData']['lastName'],
				'receiver_name' => $order['shippingData']['address']['receiverName'],
				'receiver_phone' => $order['clientProfileData']['phone'],
				'email' => $order['clientProfileData']['email'],
				'document' => $order['clientProfileData']['document'],
				'address' => [
					'street_name' => $order['shippingData']['address']['street'],
					'street_number' => $order['shippingData']['address']['number'],
					'between_1' => '---',
					'other_info' => '---',
					'zip_code' => $order['shippingData']['address']['postalCode'],
					'city' => $order['shippingData']['address']['city'],
					'neighborhood_name' => $order['shippingData']['address']['neighborhood'] ?? '---',
					'state' => $order['shippingData']['address']['state'],
				]
			]
		];

		foreach ($order['itemMetadata']['Items'] as $index => $item) {
			$data['shipments'][0]['items'][] = [
				'item' => $item['Name'],
				'sku' => $item['SkuName'],
				'quantity' => $order['items'][$index]['quantity']
			];
		}

		$response = $rest->post($url, $data, [
			'Authorization' => "Bearer $token"
		]);

		if (!$response) {
            Log::write('error', 'Error al crear la orden de iflow. Revisar log.');
			return false;
		}

		return $response['success'] ? $response['results'] : false;
	}

	private function getNumber($value)
	{
		return $value * 1e-2;
	}
}
