<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\Http\ServerRequest;

class ModelComponent extends Component {
	private $request;
	public $components = ['Image'];

	public function initialize(array $config): void
	{
		parent::initialize($config);
		$this->request = $this->getController()->getRequest();
	}

	/**
	 * @param String $params.name
	 * @param Array  $params.foreignKey
	 * @param String $params.image
	 */
	public function save(array $params): bool
	{
		$data_json = $this->request->getData($params['name']);
		if (!$data_json) {
			return false;
		}
		$data_json = $data_json['json'];
		if (!$data_json) {
			return false;
		}
		$data = json_decode($data_json, true);
		if (empty($data)) {
			return false;
		}

		$model = TableRegistry::get($params['name']);
		$field_image = $params['image'] ?? false;
		$foreignKeyName = $params['foreignKey'][0];
		$foreignKeyValue = $params['foreignKey'][1];
		$new_ids = [];

		foreach ($data['add'] as $row) {
			$dataEntity = $row;
			if ($field_image && isset($row[$field_image]) && isset($row[$field_image . '_ext'])) {
				$dataEntity[$field_image] = uniqid() . '.' . $row[$field_image . '_ext'];
				unset($dataEntity[$field_image . '_ext']);
			}
			$dataEntity[$foreignKeyName] = $foreignKeyValue;

			$entity = $model->newEmptyEntity();
			$entity = $model->patchEntity($entity, $dataEntity);
			if ($model->save($entity)) {
				if ($field_image && isset($row[$field_image]) && isset($row[$field_image . '_ext'])) {
					$this->Image->save($row[$field_image], PATH_UPLOAD . $dataEntity[$field_image]);
				}
				$new_ids[$dataEntity['id_temp']] = $entity->id;
			}
		}

		foreach ($data['edit'] as $row) {
			$dataEntity = $row;
			if ($field_image && isset($row[$field_image]) && isset($row[$field_image . '_ext'])) {
				$dataEntity[$field_image] = uniqid() . '.' . $row[$field_image . '_ext'];
				unset($dataEntity[$field_image . '_ext'], $dataEntity[$field_image . '_old']);
			}
			$dataEntity[$foreignKeyName] = $foreignKeyValue;

			$entity = $model->get($row['id']);
			$entity = $model->patchEntity($entity, $dataEntity);
			if ($model->save($entity)) {
				if ($field_image && isset($row[$field_image]) && isset($row[$field_image . '_ext'])) {
					$this->Image->save($row[$field_image], PATH_UPLOAD . $dataEntity[$field_image]);
					if ($row[$field_image . '_old'] && file_exists(PATH_UPLOAD . $row[$field_image . '_old'])) {
						unlink(PATH_UPLOAD . $row[$field_image . '_old']);
					}
				}
			}
		}

		foreach ($data['remove'] as $id) {
			$entity = $model->get($id);
			if ($field_image) {
				$image_src = $entity->{$field_image};
			}
			if ($model->delete($entity)) {
				if (!empty($image_src) && file_exists(PATH_UPLOAD . $image_src)) {
					unlink(PATH_UPLOAD . $image_src);
				}
			}
		}


		if (isset($data['sort'])) {
			$position = 0;
			foreach ($data['sort'] as $row) {
				if (isset($row['id_temp'])) {
					$id = $new_ids[$row['id_temp']];
				} else {
					$id = $row['id'];
				}
				$entity = $model->get($id);
				$entity->position = ++$position;
				$model->save($entity);
			}
		}

		return true;
	}

}
