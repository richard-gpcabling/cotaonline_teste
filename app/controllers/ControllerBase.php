<?php

use Phalcon\Mvc\Controller;
use App\Services;

class ControllerBase extends Controller
{

    protected function initialize()
    {
        try {
            $this->tag->prependTitle('GP Cabling | ');
            $this->view->setTemplateAfter('main');
            $this->view->auth = $this->session->get('auth');
        } catch (\Exception $e) {
            echo get_class($e), ": ", $e->getMessage(), "\n";
            echo " File=", $e->getFile(), "\n";
            echo " Line=", $e->getLine(), "\n";
            echo $e->getTraceAsString();
        }
    }
}
