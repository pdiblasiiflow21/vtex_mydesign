<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\ORM\TableRegistry;
use Cake\Event\EventInterface;
use Cake\Log\Log;
use Cake\Console\ShellDispatcher;

class StoresController extends AppController
{
	public function beforeFilter(EventInterface $event)
	{
		$this->Security->setConfig('unlockedActions', ['hookOrder']);
		$this->Authentication->addUnauthenticatedActions(['hookOrder']);
	}

	public function hookOrder()
	{
		$this->autoRender = false;

		$shell = new ShellDispatcher();
		$method = $_SERVER['REQUEST_METHOD'];
		$headers = apache_request_headers();
		$requestData = $this->request->getData();
		$queryParams = $this->request->getQueryParams();

		Log::write('info', 'Request method: ' . $method);
		Log::write('info', 'Headers: ' . json_encode($headers));
		Log::write('info', 'Request data: ' . json_encode($requestData));
		Log::write('info', 'Query params: ' . json_encode($queryParams));

		if (!($requestData && isset($requestData['OrderId']) && isset($requestData['Origin']['Account']))) {
			Log::write('info', 'Me falta el OrderId | Origin.Account');
			return;
		}

		$output = $shell->run(['cake', 'cronjob', 'tracking_by_order', $requestData['OrderId'], $requestData['Origin']['Account']]);

		if ($output === 0) {
			echo 'Ok';
		} else {
			echo 'Error';
		}
	}

}