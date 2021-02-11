<?php

class MultipledbController extends ControllerBase
{
    public function initialize()
    {
        $this->tag->setTitle('sdfs');
        parent::initialize();
    }

    public function indexAction()
    {
    	$testinho = TesteMultiple::findFirstByCodigoProduto('49037');
    	var_dump($testinho->codigo_produto);
    }
}
