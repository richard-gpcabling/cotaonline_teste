<?php

namespace App\Library\Email;

use App\Helpers\UtilHelper;
use Phalcon\Di;

class Usuario
{
    /**
     * @var \Phalcon\DiInterface|null
     */
    private $di;

    /**
     * @var EmailAutomatico
     */
    private $emailLibrary;

    public function __construct()
    {
        $this->di = Di\FactoryDefault::getDefault();
        $this->emailLibrary = new EmailAutomatico();
    }

    /**
     * Envia cadastro confirmação
     *
     * @param int $id_usuario
     * @return array
     */
    public function enviaCadastroConfirmacao($id_usuario)
    {
        $gatilhos = \EmailAutomatico::getListByGatilhoActive(EmailAutomatico::GT_USUARIO_CADASTRO_CONFIRMACAO);
        if (count($gatilhos) < 1) {
            return ['status' => 'success', 'message' => 'Nenhum gatilho ativo para Confirmação de Cadastro'];
        }

        // Get data to email
        $data = $this->loadDataCadastroConfirmacao($id_usuario);
        if (is_null($data)) {
            return ['status' => 'error', 'message' => 'Usuário não encontrado'];
        }

        $success = true;
        foreach ($gatilhos as $gatilho) {
            $assunto = $this->emailLibrary->setDataTexto($data, $gatilho->emailAutomatico->assunto);
            $mensagem = $this->emailLibrary->setDataTexto($data, $gatilho->emailAutomatico->mensagem);
            $cc = json_decode($gatilho->emailAutomatico->cc, true);
            $cco = json_decode($gatilho->emailAutomatico->cco, true);

            $from = [
                'name' => $gatilho->emailAutomaticoRemetente->descricao,
                'username' => $gatilho->emailAutomaticoRemetente->username,
                'password' => $gatilho->emailAutomaticoRemetente->password,
                'port' => $gatilho->emailAutomaticoRemetente->port,
            ];

            if ((int) $gatilho->emailAutomatico->usuario_recebe === 1) {
                $to = [['email' => $data['email_usuario'], 'name' => $data['nome_usuario']]];
                $replyTo = ['email' => $gatilho->emailAutomatico->reply_to, 'name' => $from['name']];
            } else {
                $to = '';
                $replyTo = ['email' => $data['email_usuario'], 'name' => $data['nome_usuario']];
            }

            // Envia email
            $retorno = $this->emailLibrary->enviaEmail(
                $from,
                $replyTo,
                $to,
                $cc,
                $cco,
                $assunto,
                $mensagem
            );

            if ($retorno !== true) {
                $success = false;
            }
        }
        if ($success === true) {
            return ['status' => 'success', 'message' => 'Email enviado com sucesso'];
        } else {
            return ['status' => 'error', 'message' => 'Não foi possível enviar o email'];
        }
    }

    /**
     * Get usuário data
     *
     * @param int $id_usuario
     * @return null
     */
    private function loadDataCadastroConfirmacao($id_usuario)
    {
        $usuario = \Usuario::findFirst($id_usuario);
        if (!$usuario) {
            return null;
        }

        $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'gpcabling.com.br';

        $link = "http://" .  $host . "/register";
        $link .= '?action=confirm&email=' . $usuario->email;
        $link .= '&code=' . $usuario->confirm_code;

        $data['nome_usuario'] = $usuario->name;
        $data['primeiro_nome_usuario'] = UtilHelper::substrFirstOcurrence($usuario->name, ' ');
        $data['email_usuario'] = $usuario->email;
        $data['link_confirmacao'] = '<a href="' . $link . '">CONFIRME AQUI</a>';

        return $data;
    }

    /**
     * Esqueceu a senha
     *
     * @param int $id_usuario
     * @return array
     */
    public function enviaForgotPassword($id_usuario)
    {
        $gatilhos = \EmailAutomatico::getListByGatilhoActive(EmailAutomatico::GT_USUARIO_SENHA_ESQUECEU);
        if (count($gatilhos) < 1) {
            return ['status' => 'success', 'message' => 'Nenhum gatilho encontrado para Recuperação de Senha'];
        }

        // Get data to email
        $data = $this->loadDataForgotPassword($id_usuario);

        $success = true;
        foreach ($gatilhos as $gatilho) {
            $assunto = $this->emailLibrary->setDataTexto($data, $gatilho->emailAutomatico->assunto);
            $mensagem = $this->emailLibrary->setDataTexto($data, $gatilho->emailAutomatico->mensagem);
            $cc = json_decode($gatilho->emailAutomatico->cc, true);
            $cco = json_decode($gatilho->emailAutomatico->cco, true);

            $from = [
                'name' => $gatilho->emailAutomaticoRemetente->descricao,
                'username' => $gatilho->emailAutomaticoRemetente->username,
                'password' => $gatilho->emailAutomaticoRemetente->password,
                'port' => $gatilho->emailAutomaticoRemetente->port,
            ];

            $to = [['email' => $data['email_usuario'], 'name' => $data['nome_completo_usuario']]];
            $replyTo = ['email' => $gatilho->emailAutomatico->reply_to, 'name' => 'Não responda'];

            // Envia email
            $retorno = $this->emailLibrary->enviaEmail(
                $from,
                $replyTo,
                $to,
                $cc,
                $cco,
                $assunto,
                $mensagem
            );

            if ($retorno !== true) {
                $success = false;
            }
        }
        if ($success === true) {
            return ['status' => 'success', 'message' => 'Email enviado com sucesso'];
        } else {
            return ['status' => 'error', 'message' => 'Não foi possível enviar o email'];
        }
    }

    /**
     * Load post data
     *
     * @param array $data
     * @return array
     */
    private function loadDataForgotPassword($id_usuario)
    {
        $usuario = \Usuario::findFirst($id_usuario);
        if (!$usuario) {
            return null;
        }

        $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'gpcabling.com.br';

        $link = "http://" .  $host . "/session/retrieve";
        $link .= '?email=' . $usuario->email;
        $link .= '&code=' . $usuario->confirm_code;

        $result = [
            'email_usuario' => $usuario->email,
            'nome_completo_usuario' => $usuario->name,
            'primeiro_nome_usuario' => UtilHelper::substrFirstOcurrence($usuario->name, ' '),
            'link' => '<a href="' . $link . '">RESETAR SENHA</a>'
        ];
        return $result;
    }
}
