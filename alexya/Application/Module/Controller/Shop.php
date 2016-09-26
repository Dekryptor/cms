<?php
namespace Application\Module\Controller;

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
}
