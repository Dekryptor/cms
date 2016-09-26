<?php
namespace Application\ORM\Account\Equipment;

use \Alexya\Database\ORM\Model;

/**
 * Model for `accounts_equipment_items` table.
 *
 * @author Manulaiko <manulaiko@gmail.com>
 */
class Item extends Model
{
    /**
     * Table name.
     *
     * @var string
     */
    protected static $_table = "accounts_equipment_items";

    /**
     * Relations.
     *
     * @var array
     */
    protected static $_relations = [
        "items" => [
            "name" => "Item"
        ]
    ];
}
