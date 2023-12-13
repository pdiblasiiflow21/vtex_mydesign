<?php
namespace App\Shell\Helper;

use Cake\Console\Helper;
use Cake\Http\Client;
use Cake\Console\ConsoleIo;
use Cake\Log\Log;

class RestHelper extends Helper
{
	private $http;
	private $packages;
	private $delay = 0;
	public $successful = null;
	public $acceptJson = false;
	public $logTitle = 'REQUEST';

	public function output($args): void
	{
	}

    public function __construct(ConsoleIo $io, array $config = [])
	{
		parent::__construct($io, $config);
		$this->http = new Client();
		$this->packages = [ 'success' => [], 'errors' => [] ];
	}

	public function get(string $url, $data = [], ?array $header = [])
	{
		return $this->request('get', $url, $data, $header);
	}

	public function post(string $url, $data = [], ?array $header = [])
	{
		return $this->request('post', $url, $data, $header);
	}

	public function patch(string $url, $data = [], ?array $header = [])
	{
		return $this->request('patch', $url, $data, $header);
	}

	public function getSuccess()
	{
		return $this->packages['success'];
	}

	public function getErrors()
	{
		return $this->packages['errors'];
	}

	public function getPackages()
	{
		return $this->packages;
	}

	public function reset()
	{
		$this->packages = [ 'success' => [], 'errors' => [] ];
		$this->successful = null;
		$this->acceptJson = false;
		$this->logTitle = 'REQUEST';
		$this->delay = 0;
	}

	public function setDelay(int $delay)
	{
		$this->delay = $delay;
	}

	private function request($method, string $url, $data = [], ?array $header = [])
	{
		$headerDef = $this->getConfig('header') ?? [];
		$header += $headerDef;

		if (!isset($header['Content-Type']) && is_array($data) && $data) {
			$header['Content-Type'] = 'application/json';
		}

		if ($this->acceptJson) {
			$header['Accept'] = 'application/json';
		}

		if (is_array($data) && $data) {
			$data = json_encode($data);
		}
	
		Log::write('info', "[{$this->logTitle}] $url");

		$options = [];
		if ($header) {
			$options['headers'] = $header;
		}

		if ($this->delay) {
			usleep($this->delay);
		}

		$response = $this->http->{$method}($url, $data, $options);
		$body = @json_decode($response->getStringBody(), true);
		$status = $response->getStatusCode();

		$ok = false;
		
		if ($status >= 200 && $status <= 204) {
			$ok = true;
		}
		if ($this->acceptJson) {
			$ok = !empty($body);
		}

		if ($this->successful === null) {
			$this->successful = $ok;
		}

		$package = compact('method', 'url', 'data', 'header');

		if (!$ok) {
			$this->packages['errors'][] = $package;
			$method = strtoupper($method);
			$msg = "[{$this->logTitle}] $method $url\n";
			$msg .= "Header: \n" . json_encode($header) . "\n";
			$msg .= "Data: \n" . (is_string($data) ? $data : json_encode($data)) . "\n";
			$msg .= "Status: $status\n";
			$msg .= "Response: \n" . $response->getStringBody();
			Log::write('error', $msg);
			return false;
		}

		$this->packages['success'][] = $package;

		return $body;
	}

}