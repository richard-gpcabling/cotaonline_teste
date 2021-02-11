<?php

namespace Test;

use App\Forms\EmailAutomaticoForm;
use App\Library\Email\Contato;
use App\Library\Email\EmailAutomatico;
use App\Library\Email\Orcamento;
use App\Library\Email\Usuario;
use UnitTestCase;

/**
 * ProdutoCategoriaTest
 *
 * @author Leandro <leandro@policom.com.br>
 */
class EmailAutomaticoTest extends UnitTestCase
{

    public function testGatilho()
    {
        $this->markTestSkipped('Skipped');

        $email = new EmailAutomatico();

        $gatilho = $email->getGatilho(EmailAutomatico::GT_USUARIO_CADASTRO_CONFIRMACAO);

        $this->assertTrue(isset($gatilho['id']) && $gatilho['id'] === EmailAutomatico::GT_USUARIO_CADASTRO_CONFIRMACAO);
    }

    public function testFormValidation()
    {
        $this->markTestSkipped('Skipped');

        $form = new EmailAutomaticoForm(new \EmailAutomatico(), ['edit' => true]);

        $data = ['id' => 1, 'nome' => 'teste', 'status' => 'inactive'];

        $valid = $form->isValid($data);

        $this->assertTrue($valid);
    }

    public function testContato()
    {
        $this->markTestSkipped('Skipped');

        $contato = new Contato();

        $post = [
            'nome_completo' => 'Leandro Teixeira',
            'email_cli' => 'leandro@policom.com.br',
            'mensagem' => 'Lorem ipsum dolor sit amet consectetur'
        ];

        $result = $contato->envia($post);

        $expected = 'success';

        $this->assertEquals($expected, $result['status']);
    }

    public function testUsuarioCadastroEmailConfirmacao()
    {
        $this->markTestSkipped('Skipped');

        $usuario = new Usuario();
        $result = $usuario->enviaCadastroConfirmacao(5749);

        $expected = 'success';

        $this->assertEquals($expected, $result['status']);
    }

    public function testForgotPassword()
    {
        $this->markTestSkipped('Skipped');

        $usuario = new Usuario();
        $result = $usuario->enviaForgotPassword(4);

        $expected = 'success';

        $this->assertEquals($expected, $result['status']);
    }

    public function testOrcamento()
    {
        $this->markTestSkipped('Skipped');

        $usuario = new Orcamento();
        $result = $usuario->enviaCadastro(1724);

        $expected = 'success';

        $this->assertEquals($expected, $result['status']);
    }
}
