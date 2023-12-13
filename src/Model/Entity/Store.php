<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Store Entity
 *
 * @property int $id
 * @property string $appkey
 * @property string $apptoken
 * @property string $environment
 * @property string $account_name
 * @property string $city
 * @property string $state
 * @property string $street
 * @property string $postal_code
 * @property int $street_number
 *
 */
class Store extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'appkey' => true,
        'apptoken' => true,
        'environment' => true,
        'account_name' => true,
        'city' => true,
        'state' => true,
        'street' => true,
        'postal_code' => true,
        'street_number' => true,
        'user_api_iflow' => true,
        'pass_api_iflow' => true,
    ];
}
