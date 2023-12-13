<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Routing\Router;
use Cake\Http\Client;

/**
 * Stores Controller
 *
 * @property \App\Model\Table\StoresTable $Stores
 * @method \App\Model\Entity\Store[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class StoresController extends AppController
{
	/**
	 * Index method
	 *
	 * @return \Cake\Http\Response|null|void Renders view
	 */
	public function index()
	{
		$stores = $this->paginate($this->Stores);

		$this->set(compact('stores'));
	}

	/**
	 * Add method
	 *
	 * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
	 */
	public function add()
	{
		$store = $this->Stores->newEmptyEntity();
		if ($this->request->is('post')) {
			$store = $this->Stores->patchEntity($store, $this->request->getData());
			if ($this->Stores->save($store)) {
				$this->Flash->success('La tienda ha sido guardada.');

				$this->configHookVtex($store);

				return $this->redirect(['action' => 'index']);
			}
			$this->Flash->error('No se pudo guardar la tienda. Inténtalo de nuevo.');
		}
		$this->set(compact('store'));
	}

	/**
	 * Edit method
	 *
	 * @param string|null $id Store id.
	 * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
	 * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
	 */
	public function edit($id = null)
	{
		$store = $this->Stores->get($id, [
			'contain' => [],
		]);
		if ($this->request->is(['patch', 'post', 'put'])) {
			$store = $this->Stores->patchEntity($store, $this->request->getData());
			if ($this->Stores->save($store)) {
				$this->Flash->success('La tienda ha sido guardada.');

				$this->configHookVtex($store);

				return $this->redirect(['action' => 'index']);
			}
			$this->Flash->error('No se pudo guardar la tienda. Inténtalo de nuevo.');
		}
		$this->set(compact('store'));
	}

	/**
	 * Delete method
	 *
	 * @param string|null $id Store id.
	 * @return \Cake\Http\Response|null|void Redirects to index.
	 * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
	 */
	public function delete($id = null)
	{
		$this->request->allowMethod(['post', 'delete']);
		$store = $this->Stores->get($id);
		if ($this->Stores->delete($store)) {
			$this->Flash->success('La tienda ha sido eliminada.');
		} else {
			$this->Flash->error('No se pudo eliminar la tienda. Inténtalo de nuevo.');
		}

		return $this->redirect(['action' => 'index']);
	}

	public function view($id)
	{
		$data = $this->Stores->get($id);
		$this->set(compact('data'));
	}

	private function configHookVtex($store)
	{
		$http = new Client();
		$url = "https://{$store['account_name']}.{$store['environment']}.com.br/api/orders/hook/config";
		$hook_url = Router::url(['controller' => 'Stores', 'action' => 'hook_order', 'prefix' => false], true);
		$response = $http->post($url, json_encode([
			'filter' => [
				'status' => ['order-completed', 'handling', 'ready-for-handling', 'waiting-ffmt-authorization', 'invoiced']
			],
			'hook' => [
				'url' => $hook_url,
				'headers' => [
					'store_id' => $store['id'],
				]
			]
		]), [
			'headers' => [
				'Content-Type' => 'application/json',
				'X-VTEX-API-AppKey' => $store['appkey'],
				'X-VTEX-API-AppToken' => $store['apptoken']
			]
		]);

		/*$status = $response->getStatusCode();
		$body = @json_decode($response->getStringBody(), true);*/
	}

}
