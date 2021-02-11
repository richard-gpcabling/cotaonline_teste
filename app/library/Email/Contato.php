<?php

namespace App\Library\Email;

use App\Helpers\UtilHelper;
use Phalcon\Di;

class Contato
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
    public function envia($post)
    {
        $gatilhos = \EmailAutomatico::getListByGatilhoActive(EmailAutomatico::GT_CONTATO);
        if (count($gatilhos) < 1) {
            return ['status' => 'success', 'message' => 'Nenhum gatilho ativo para Confirmação de Cadastro'];
        }

        $data = $this->loadPostData($post);

        $success = true;
        foreach ($gatilhos as $gatilho) {
            $assunto = $this->emailLibrary->setDataTexto($data, $gatilho->emailAutomatico->assunto);
            $mensagem = $this->emailLibrary->setDataTexto($data, $gatilho->emailAutomatico->mensagem);
            $cc = json_decode($gatilho->emailAutomatico->cc, true);
            $cco = json_decode($gatilho->emailAutomatico->cco, true);

            $from = [
                'name' => 'Contato',
                'username' => $gatilho->emailAutomaticoRemetente->username,
                'password' => $gatilho->emailAutomaticoRemetente->password,
                'port' => $gatilho->emailAutomaticoRemetente->port,
            ];

            if ((int) $gatilho->emailAutomatico->usuario_recebe === 1) {
                $to = [['email' => $data['e_mail'], 'name' => $data['nome_completo']]];
                $replyTo = ['email' => $gatilho->emailAutomatico->reply_to, 'name' => 'GP Cabling'];
            } else {
                $to = '';
                $replyTo = ['email' => $data['e_mail'], 'name' => $data['nome_completo']];
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
     * Load post data
     *
     * @param array $data
     * @return array
     */
    private function loadPostData($data)
    {
        $keys = [
            'nome_completo' => 'Não preenchido',
            'empresa' => 'Não preenchido',
            'cnpj' => 'Não preenchido',
            'e_mail' => 'Não preenchido',
            'tel_com' => 'Não preenchido',
            'celular' => 'Não preenchido',
            'mensagem' => 'Não preenchido'
        ];

        $result =  UtilHelper::array_union($keys, $data);
        $result['nome_completo'] = UtilHelper::mbConvertCaseTitle($result['nome_completo']);
        $result['primeiro_nome'] = UtilHelper::substrFirstOcurrence($result['nome_completo'], ' ');

        return $result;
    }
}
