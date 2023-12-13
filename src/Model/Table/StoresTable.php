<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Stores Model
 *
 * @method \App\Model\Entity\Store newEmptyEntity()
 * @method \App\Model\Entity\Store newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Store[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Store get($primaryKey, $options = [])
 * @method \App\Model\Entity\Store findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Store patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Store[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Store|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Store saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Store[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Store[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Store[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Store[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class StoresTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('stores');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('appkey')
            ->requirePresence('appkey', 'create', 'El APP Key es obligatorio.')
            ->notEmptyString('appkey', 'El APP Key es obligatorio.');

        $validator
            ->scalar('apptoken')
            ->requirePresence('apptoken', 'create', 'El APP Token es obligatorio.')
            ->notEmptyString('apptoken', 'El APP Token es obligatorio.');

        $validator
            ->scalar('environment')
            ->maxLength('environment', 255)
            ->requirePresence('environment', 'create', 'El ambiente es obligatorio.')
            ->notEmptyString('environment', 'El ambiente es obligatorio.');

        $validator
            ->scalar('account_name')
            ->maxLength('account_name', 255)
            ->requirePresence('account_name', 'create', 'La cuenta es obligatoria.')
            ->notEmptyString('account_name', 'La cuenta es obligatoria.');

        $validator
            ->scalar('city')
            ->maxLength('city', 255)
            ->requirePresence('city', 'create', 'La ciudad es obligatorio.')
            ->notEmptyString('city', 'La ciudad es obligatorio.');

        $validator
            ->scalar('state')
            ->maxLength('state', 255)
            ->requirePresence('state', 'create', 'La provincia es obligatorio.')
            ->notEmptyString('state', 'La provincia es obligatorio.');

        $validator
            ->scalar('street')
            ->maxLength('street', 255)
            ->requirePresence('street', 'create', 'La calle es obligatorio.')
            ->notEmptyString('street', 'La calle es obligatorio.');

        $validator
            ->scalar('postal_code')
            ->maxLength('postal_code', 255)
            ->requirePresence('postal_code', 'create', 'El código postal es obligatorio.')
            ->notEmptyString('postal_code', 'El código postal es obligatorio.');

        $validator
            ->integer('street_number')
            ->requirePresence('street_number', 'create', 'El número de calle es obligatorio.')
            ->notEmptyString('street_number', 'El número de calle es obligatorio.');

        $validator
            ->scalar('user_api_iflow')
            ->requirePresence('user_api_iflow', 'create', 'El usuario de la api es obligatorio.')
            ->notEmptyString('user_api_iflow', 'El usuario de la api es obligatorio.');

        $validator
            ->scalar('pass_api_iflow')
            ->requirePresence('pass_api_iflow', 'create', 'La contraseña de la api es obligatorio.')
            ->notEmptyString('pass_api_iflow', 'La contraseña de la api es obligatorio.');

        return $validator;
    }
}
