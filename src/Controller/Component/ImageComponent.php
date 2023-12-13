<?php
namespace App\Controller\Component;

use Cake\Controller\Component;

class ImageComponent extends Component {
	public function initialize(array $config): void
	{
		parent::initialize($config);
	}

	public function get_filename($file): string
	{
		$filename = $file->getClientFilename();
		if ($filename) {
			$name = explode('.', strtolower($filename));
			$extension = array_pop($name);
			return uniqid() . '.' . $extension;
		}

		return '';
	}

	public function save($image_base64, $image_src)
	{
		$fp = fopen($image_src, 'wb'); 
		$dataImage = explode(',', $image_base64);
		fwrite($fp, base64_decode($dataImage[1])); 
		fclose($fp);
	}

}
