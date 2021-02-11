<?php

class ReportController extends ControllerBase
{
    public function initialize()
    {
        $this->tag->setTitle('Report');
        parent::initialize();
    }

    public function indexAction()
    {
        
    }

    public function fabricanteAction($ano,$fabricante=null)
    {
        
        echo $fabricante."----".$ano;
    }
}

