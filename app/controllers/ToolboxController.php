<?php

class ToolboxController extends ControllerBase
{
    public function initialize()
    {
        $this->tag->setTitle('Toolbox');
        parent::initialize();
    }

    public function indexAction()
    {
    }

    /**
     * Teste utilizando um Model com retorno de Json a partir de um endpoint.
     * Tentar fazer o endpoint receber parametros. Caso, receba, ele faz um GET
     * parametrizado. Ou fazer um endpoint de cada model...
     */
    public function jsontestAction()
    {
        $produtos = MyTest::getData();

        foreach ($produtos as $item):
            var_dump($item);
        endforeach;

    }
}
