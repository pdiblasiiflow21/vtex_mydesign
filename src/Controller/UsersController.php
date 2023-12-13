<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
	public function beforeFilter(\Cake\Event\EventInterface $event)
	{
		parent::beforeFilter($event);
		// Configure the login action to not require authentication, preventing
		// the infinite redirect loop issue
		$this->Authentication->addUnauthenticatedActions(['login']);
	}
	
	public function login()
	{
		//echo (new \Authentication\PasswordHasher\DefaultPasswordHasher())->hash('1234');die;

		$this->request->allowMethod(['get', 'post']);
		$result = $this->Authentication->getResult();
		// regardless of POST or GET, redirect if user is logged in
		if ($result && $result->isValid()) {
			return $this->redirect(['controller' => 'Stores', 'action' => 'index', 'prefix' => 'Admin']);
		}
		// display error if user submitted and authentication failed
		if ($this->request->is('post') && !$result->isValid()) {
			//debug($result->getErrors());die;
			$this->Flash->error('El usuario o contraseña es incorrecto');
		}

		$user = $this->Users->newEmptyEntity();
		$this->set(compact('user'));
	}

	public function logout()
	{
		$result = $this->Authentication->getResult();
		// regardless of POST or GET, redirect if user is logged in
		if ($result && $result->isValid()) {
			$this->Authentication->logout();
			return $this->redirect(['controller' => 'Users', 'action' => 'login']);
		}
	}
}