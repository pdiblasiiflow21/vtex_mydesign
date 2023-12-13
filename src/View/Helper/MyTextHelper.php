<?php
namespace App\View\Helper;

use Cake\View\Helper;

class MyTextHelper extends Helper
{
	/**
	 * @param Int $month  Número del mes
	 */
	public function getMonthName($month)
	{
		$months = [
			'Enero', 
			'Febrero',
			'Marzo',
			'Abril',
			'Mayo',
			'Junio',
			'Julio',
			'Agosto',
			'Septiembre',
			'Octubre',
			'Noviembre',
			'Diciembre'
		];
		return $months[$month - 1];
	}

	/**
	 * @param FrozenTime $date
	 */
	public function showDate($date)
	{
		return $date->i18nFormat('dd') . ' de ' . $this->getMonthName($date->i18nFormat('M')) . ' de ' . $date->i18nFormat('YYYY');
	}

	/**
	 * [
	 * 		[
	 * 			'name' => 'Blog',
	 * 			'url' => 'https://example.com/blog'
	 * 		],
	 * 		[
	 * 			'name' => 'eCommerce',
	 * 			'url' => 'https://example.com/blog/ecommerce'
	 * 		],
	 * 		[
	 * 			'name' => 'Test',
	 * 			'url' => 'https://example.com/blog/ecommerce/test'
	 * 		],
	 * ]
	 * 
	 * @param Array $crumbs
	 */
	public function breadcrumb()
	{
		$crumbs = $this->_View->get('crumbs');
		if (empty($crumbs)) {
			return;
		}
		$length = count($crumbs) - 1;
		echo '<div class="mx-breadcrumb">';
		echo '<ol itemscope itemtype="https://schema.org/BreadcrumbList">';
		foreach ($crumbs as $index => $crumb) {
			echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
			if ($index == $length) {
				echo '<span>' . $crumb['name'] . '</span>';
			} else {
				echo '<a itemprop="item" href="' . $crumb['url'] . '"><span itemprop="name">' . $crumb['name'] .'</span></a>';
			}
			echo '<meta itemprop="position" content="' . ($index+1) . '" />';
			echo '</li>';
		}
		echo '</ol>';
		echo '</div>';
	}

}
