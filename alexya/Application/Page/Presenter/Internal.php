<?php
namespace Application\Page\Presenter;

use \Alexya\Foundation\Presenter;
use \Alexya\Database\ORM\Model;

/**
 * Internal page presenter.
 *
 * Sets the variables for rendering the internal page view.
 *
 * @author Manulaiko <manulaiko@gmail.com>
 */
class Internal extends Presenter
{
    /**
     * Sets View's variables.
     */
    public function onInstance()
    {
        $this->_triad->View->set("name", ($this->_request->uri()[2] ?? ""));
        // Call the render method here so it redirects now instead of when the view is being rendered.
        $this->_triad->View->set("module", $this->_triad->children->module->Controller->render());
        $this->_triad->View->set("news", Model::latest(10, "id", "news"));
    }
}
