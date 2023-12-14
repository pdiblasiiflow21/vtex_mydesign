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

	public function getToken()
	{
		if ($this->cache_token) {
			return $this->cache_token;
		}
		$url = $this->getConfig('rest.host_api') . '/api/login';
		$rest = $this->_io->helper('Rest');
		$rest->reset();
		$response = $rest->post($url, [
			'_username' => $this->getConfig('rest.api_client'),
			'_password' => $this->getConfig('rest.api_key')
		]);

        // Log::write('info', json_encode($response, true));
		if (!$response) {
			return false;
		}
		$this->cache_token = $response['token'];
		return $response['token'];
	}

	public function createOrder($order)
	{
		Log::write('info', 'Creando orden en iFlow');
		$url = $this->getConfig('rest.host_api') . '/api/order/create';
		$rest = $this->_io->helper('Rest');
		$rest->reset();
		$rest->logTitle = 'REQUEST IFLOW';
		$rest->acceptJson = true;
		$token = $this->getToken();

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
			return false;
		}

		return $response['success'] ? $response['results'] : false;
	}

	private function getNumber($value)
	{
		return $value * 1e-2;
	}
}
