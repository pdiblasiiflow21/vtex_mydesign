<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Order Entity
 *
 * @property int $id
 * @property string $order_id_vtex
 * @property string $tracking_id
 * @property string $invoice_number
 * @property string $response_order_iflow
 * @property string $response_tracking_vtex
 * @property \Cake\I18n\FrozenTime|null $created
 */
class Order extends Entity
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
        'order_id_vtex' => true,
        'tracking_id' => true,
        'invoice_number' => true,
        'response_order_iflow' => true,
        'response_tracking_vtex' => true,
        'created' => true,
    ];
}
