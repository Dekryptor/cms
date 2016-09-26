<?php
namespace Application\Module\Presenter;

use \Alexya\Database\ORM\Model;
use \Alexya\Foundation\Presenter;
use \Alexya\Http\Response;
use \Alexya\Container;

/**
 * Shop page presenter.
 *
 * @author Manulaiko <manulaiko@gmail.com>
 */
class Shop extends Presenter
{
    /**
     * Sets view's variables.
     */
    public function onInstance()
    {
        $category = strtolower(($this->_request->uri()[1] ?? ""));
        $items    = Model::all([
            "category" => $category
        ], "items");

        if(empty($items)) {
            Response::redirect("/Internal/Shop/Ships");
        }

        $this->_triad->View->set("items", $items);
    }
}
