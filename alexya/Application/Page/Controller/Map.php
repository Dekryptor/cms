<?php
namespace Application\Page\Controller;

use \Alexya\Foundation\Controller;

/**
 * Map page controller.
 *
 * @author Manulaiko <manulaiko@gmail.com>
 */
class Map extends Controller
{
    /**
     * Main action.
     *
     * @return string Map page content.
     */
    public function index() : string
    {
        return $this->_triad->View->render();
    }
}
