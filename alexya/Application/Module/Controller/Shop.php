<?php
namespace Application\Module\Controller;

use \Alexya\Container;
use \Alexya\Database\ORM\Model;
use \Alexya\Foundation\Controller;

/**
 * Shop page controller
 *
 * @author Manulaiko <manulaiko@gmail.com>
 */
class Shop extends Controller
{
    /**
     * Renders the page and returns it.
     *
     * @return string Shop page content.
     */
    public function index() : string
    {
        return $this->_triad->Presenter->render();
    }

    /**
     * Buys an item.
     *
     * @param int id Item id.
     *
     * @return string Response content.
     */
    public function buy($id) : string
    {
        $account = Container::Account();

        if(!is_numeric($id)) {
            Container::Logger()->warning("Possible XSS/SQL injection detected: {$id}");

            return $this->index();
        }

        $item = Model::find($id, -1, "items");
        if(empty($item)) {
            Container::Logger()->warning("Item with id {$id} not found!");

            return $this->index();
        }

        // Deduce price from account's balance
        if($item->is_elite) {
            if($account->uridium < $item->price) {
                Results::flash([
                    "result" => "warning",
                    "message" => t("You don't have enough uridium!")
                ]);

                return $this->index();
            }

            $account->urdium -= $item->price;
        } else {
            if($account->credits < $item->price) {
                Results::flash([
                    "result" => "warning",
                    "message" => t("You don't have enough credits!")
                ]);

                return $this->index();
            }

            $account->credits -= $item->price;
        }
        $account->save();

        Container::Logger()->debug("Price deduced {$item->price}: ". Container::Database()->lastQuery);

        if($item->category == "ships") {
            $this->_buyShip($item);
        } else if($item->category == "drones") {
            $this->_buyDrone($item);
        } else if($item->category == "amunition") {
            $this->_buyAmmo($item);
        } else {
            $this->_buyItem($item);
        }

        return $this->index();
    }

    /**
     * Buys a drone.
     *
     * @param \Alexya\Database\ORM\Model $item Drone item.
     */
    private function _buyDrone(Model $item)
    {
        $account = Container::Account();
        $drone   = Model::find($item->id, -1, "drones");

        // Create and insert row in `accounts_equipment_ships`
        $i = Model::create("accounts_equipment_drones");
        $i->accounts_id = $account->id;
        $i->drones_id   = $drone->id;

        $i->save();

        Container::Logger()->debug("Drone created: ". Container::Database()->lastQuery);
    }

    /**
     * Buys a ship.
     *
     * @param \Alexya\Database\ORM\Model $item Ship item.
     */
    private function _buyShip(Model $item)
    {
        $account = Container::Account();
        $ship    = Model::find($item->id, -1, "ships");

        // Create and insert row in `accounts_equipment_ships`
        $i = Model::create("accounts_equipment_ships");
        $i->accounts_id = $account->id;
        $i->ships_id    = $ship->id;
        $i->gfx         = $ship->id;
        $i->maps_id     = $account->Faction->home_maps_id;
        $i->position    = $account->Faction->home_position;
        $i->health      = $ship->health;

        $i->save();

        Container::Logger()->debug("Ship created: ". Container::Database()->lastQuery);
    }

    /**
     * Buys an item.
     *
     * @param \Alexya\Database\ORM\Model $item Item model.
     */
    private function _buyItem(Model $item)
    {
        $account = Container::Account();

        // Create and insert row in `accounts_equipment_items`
        $i = Model::create("accounts_equipment_items");
        $i->accounts_id = $account->id;
        $i->items_id    = $item->id;

        $i->save();

        Container::Logger()->debug("Item created: ". Container::Database()->lastQuery);
    }

    /**
     * Buys amunition.
     *
     * @param \Alexya\Database\ORM\Model $item Ammo model.
     */
    private function _buyAmmo(Model $item)
    {
        $account = Container::Account();

        // Get the amunition row
        $i = Model::find([
            "AND" => [
                "items_id"    => $item->id,
                "accounts_id" => $account->id
            ]
        ], -1, "accounts_equipment_items");
        $i->amount += $item->lifetime;

        Container::Logger()->debug("Retrieved item: ". Container::Database()->lastQuery);

        $i->save();

        Container::Logger()->debug("Item created: ". Container::Database()->lastQuery);
    }
}
