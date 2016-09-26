<?php
namespace Application\Module\Controller;

use \Alexya\Container;
use \Alexya\Database\ORM\Model;
use \Alexya\Foundation\Controller;
use \Alexya\Http\Response;
use \Alexya\Tools\Session\Results;

/**
 * CompanyChoose controller.
 *
 * @author Manulaiko <manulaiko@gmail.com>
 */
class CompanyChoose extends Controller
{
    /**
     * Main action.
     *
     * Renders the page.
     *
     * @return string Page content.
     */
    public function index() : string
    {
        return $this->_triad->View->render();
    }

    /**
     * Changes user's company.
     *
     * @param int $id New company ID.
     *
     * @return string Page content.
     */
    public function choose($id) : string
    {
        $account = Container::Account();

        if($account->factions_id != 0) {
            Results::flash("faction_already_choosen", [
                "result"  => "warning",
                "message" => "You've already choosed a faction!"
            ]);

            Response::redirect("/Internal/Start");
        }

        $faction = Model::find($id, -1, "factions");
        if(empty($faction)) {
            Results::flash("faction_id_does_not_exist", [
                "result"  => "warning",
                "message" => "Faction ID does not exist!"
            ]);

            return $this->index();
        }

        $account->factions_id = $faction->id;
        $account->save();

        $hangar = Model::find($account->accounts_equipment_hangars_id, -1, "accounts_equipment_hangars");
        $ship   = Model::find($hangar->accounts_equipment_ships_id, -1, "accounts_equipment_ships");
        
        $ship->position = $faction->home_position;
        $ship->maps_id  = $faction->home_maps_id;
        $ship->save();

        Response::redirect("/Internal/Start");
    }
}
