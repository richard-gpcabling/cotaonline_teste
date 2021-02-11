<?php

namespace Test;

use App\Services\ProdutoCategoriaItemService;
use LogProdutoCategoria;
use UnitTestCase;

/**
 * ProdutoCategoriaTest
 *
 * @author Leandro <leandro@policom.com.br>
 */
class ProdutoCategoriaTest extends UnitTestCase
{

    public function testGeraLog()
    {
        $this->markTestSkipped('Skipped');

        $added = LogProdutoCategoria::add(
            LogProdutoCategoria::ACAO_ADD,
            LogProdutoCategoria::ESCOPO_CATEGORIA,
            'Categoria "Teste" added',
            1,
            1
        );

        $this->assertTrue($added);
    }

    public function testItemAdd()
    {
        $this->markTestSkipped('Skipped');

        $service = new ProdutoCategoriaItemService();

        $data = ['codigo_produto' => '16903', 'categories' => [181]];

        $result = $service->addAdmin($data, 1);

        $expected = 'success';
        $this->assertEquals($expected, $result['status']);
    }
}
