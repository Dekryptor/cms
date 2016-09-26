<?php
namespace Application\API\Controller;

use \Alexya\Container;
use \Alexya\Database\ORM\Model;
use \Alexya\Foundation\Controller;

/**
 * Item's API.
 *
 * @author Manulaiko <manulaiko@gmail.com>
 */
class Items extends Controller
{
    /**
     * Default action.
     *
     * @return array Error showing that no action is available.
     */
    public function index() : array
    {
        return [
            "result" => "error",
            "error"  => t("No action is available!")
        ];
    }

    /**
     * Returns an item from the database.
     *
     * @param int id Item id.
     */
    public function item($id) : array
    {
        if(!is_numeric($id)) {
            return $this->index();
        }

        $item = Model::find($id, -1, "items");
        if(empty($item)) {
            return [
                "result" => "error",
                "error"  => t("Item does not exist!")
            ];
        }

        $result = [
            "result" => "success",
            "item"   => $item->asDecodedJSON()
        ];

        if($result["item"]["is_elite"]) {
            $result["item"]["price"] .= t(" U.");
        } else {
            $result["item"]["price"] .= t(" C.");
        }

        $result["item"]["image"] = Container::Settings()->get("application.view_vars.URL") ."img/items/". $result["item"]["category"] ."/". $result["item"]["loot_id"] .".png";

        return $result;
    }

    /**
     * Returns items from a category.
     *
     * @param string category Category name.
     * @param int    amount   Amount of items to return (< 0 = all).
     */
    public function category($category, $amount = -1) : array
    {
        if(!is_numeric($amount)) {
            return $this->index();
        }

        $items = Model::find([
            "category" => $category
        ], $amount, "items");
        if($amount < 0) {
            $items = Model::all([
                "category" => $category
            ], "items");
        }

        if(empty($items)) {
            return [
                "result" => "error",
                "error"  => t("Category does not exist!")
            ];
        }

        $result = [
            "result" => "success",
            "items"  => []
        ];

        foreach($items as $item) {
            $i = $item->asDecodedJSON();

            if($i["is_elite"]) {
                $i["price"] .= t(" U.");
            } else {
                $i["price"] .= t(" C.");
            }
            $i["image"] = Container::Settings()->get("application.view_vars.URL") ."img/items/". $category ."/". $i["loot_id"] .".png";

            $result["items"][] = $i;
        }

        return $result;
    }
}
