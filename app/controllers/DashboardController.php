<?php

class DashboardController extends ControllerBase
{
    public function initialize()
    {
        $this->tag->setTitle('Sobre nós');
        parent::initialize();
    }

    public function indexAction()
    {
    }
}
