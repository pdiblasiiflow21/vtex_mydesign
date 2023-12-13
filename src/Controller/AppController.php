<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\EventInterface;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
	/**
	 * Initialization hook method.
	 *
	 * Use this method to add common initialization code like loading components.
	 *
	 * e.g. `$this->loadComponent('Security');`
	 *
	 * @throws \Exception
	 */
	public function initialize() :void
	{
		parent::initialize();

		$this->viewBuilder()->addHelpers([
			'Form', 'Url',
			'Paginator' => [
				'templates' => [
					'nextActive' => '<li class="page-item next"><a class="page-link" rel="next" href="{{url}}">{{text}}</a></li>',
					'nextDisabled' => '<li class="page-item next disabled"><a class="page-link" href="" onclick="return false;">{{text}}</a></li>',
					'prevActive' => '<li class="page-item prev"><a class="page-link" rel="prev" href="{{url}}">{{text}}</a></li>',
					'prevDisabled' => '<li class="page-item prev disabled"><a class="page-link" href="" onclick="return false;">{{text}}</a></li>',
					'counterRange' => '{{start}} - {{end}} de {{count}}',
					'counterPages' => '{{page}} de {{pages}}',
					'first' => '<li class="page-item first"><a class="page-link" href="{{url}}">{{text}}</a></li>',
					'last' => '<li class="page-item last"><a class="page-link" href="{{url}}">{{text}}</a></li>',
					'number' => '<li class="page-item"><a class="page-link" href="{{url}}">{{text}}</a></li>',
					'current' => '<li class="page-item active"><a class="page-link" href="">{{text}}</a></li>',
					'ellipsis' => '<li class="ellipsis">&hellip;</li>',
					'sort' => '<a href="{{url}}">{{text}}</a>',
					'sortAsc' => '<a class="asc" href="{{url}}">{{text}}</a>',
					'sortDesc' => '<a class="desc" href="{{url}}">{{text}}</a>',
					'sortAscLocked' => '<a class="asc locked" href="{{url}}">{{text}}</a>',
					'sortDescLocked' => '<a class="desc locked" href="{{url}}">{{text}}</a>',
				],
			]
		]);
	
		//$this->loadComponent('RequestHandler');
		$this->loadComponent('Flash');
		$this->loadComponent('Authentication.Authentication');
        $this->loadComponent('Security');

		if ($this->request->getParam('prefix') == 'Admin') {
			$this->viewBuilder()->setLayout('admin_layout');	
		}
	}

}
