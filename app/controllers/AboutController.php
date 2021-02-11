<?php
/* */
class AboutController extends ControllerBase
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
