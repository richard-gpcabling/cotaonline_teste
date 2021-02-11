<?php

namespace App\Library\Email;

use App\Helpers\UtilHelper;
use Phalcon\Di;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * EmailAutomatico
 *
 * @package App\Library\Email
 */
class EmailAutomatico
{
    const GT_USUARIO_CADASTRO_CONFIRMACAO = 'usuario_cadastro_email_confirmacao';

    const GT_USUARIO_SENHA_ESQUECEU = 'usuario_senha_esqueceu';

    const GT_CONTATO = 'contato';

    const GT_ORCAMENTO_CRIACAO = 'orcamento_criacao';

    /**
     * @var array
     */
    private $gatilhos = [
        [
            'id' => self::GT_USUARIO_CADASTRO_CONFIRMACAO,
            'nome' => 'Confirmação de Cadastro de Usuário',
            'variaveis_possiveis' => [
                '{{nome_usuario}}',
                '{{primeiro_nome_usuario}}',
                '{{email_usuario}}',
                '{{link_confirmacao}}'
            ]
        ], [
            'id' => self::GT_USUARIO_SENHA_ESQUECEU,
            'nome' => 'Usuário esqueceu a senha',
            'variaveis_possiveis' => [
                '{{nome_completo_usuario}}',
                '{{primeiro_nome_usuario}}',
                '{{email_usuario}}',
                '{{link}}',
            ]
        ], [
            'id' => self::GT_CONTATO,
            'nome' => 'Contato',
            'variaveis_possiveis' => [
                '{{nome_completo}}',
                '{{primeiro_nome}}',
                '{{empresa}}',
                '{{cnpj}}',
                '{{e_mail}}',
                '{{tel_com}}',
                '{{celular}}',
                '{{mensagem}}',
            ]
        ], [
            'id' => self::GT_ORCAMENTO_CRIACAO,
            'nome' => 'Carrinho Confirmação',
            'variaveis_possiveis' => [
                '{{orcamento_id}}',
                '{{orcamento_valor_total}}',
                '{{nome_completo_usuario}}',
                '{{primeiro_nome_usuario}}',
                '{{empresa_dados}}',
                '{{vendedor_dados}}',
                '{{lista_dos_produtos}}'
            ]
        ]
    ];

    /**
     * @var PHPMailer
     */
    private $mailer;
    
    public function __construct()
    {
        $this->mailer = Di\FactoryDefault::getDefault()->get('mailer');
    }

    /**
     * Get specific trigger
     *
     * @param string $chave
     * @return array|null
     */
    public function getGatilho($chave)
    {
        $gatilho = null;
        foreach ($this->gatilhos as $itemGatilho) {
            if ($itemGatilho['id'] === $chave) {
                $gatilho = $itemGatilho;
                break;
            }
        }
        return $gatilho;
    }

    /**
     * Get list gatilhos
     *
     * @return array
     */
    public function getGatilhos()
    {
        return $this->gatilhos;
    }

    /**
     * Insert variables on text
     *
     * @param array  $data
     * @param string $texto
     * @return string
     */
    public function setDataTexto($data, $texto)
    {
        foreach ($data as $key => $value) {
            if (is_string($value) || is_integer($value)) {
                $texto = str_replace("{{{$key}}}", $value, $texto);
            }
        }

        // Remove all variables not existing in data
        $texto = preg_replace('/{{[.*]}}/', '', $texto);
        return $texto;
    }

    /**
     * Envia email
     *
     * @param array|string       $from
     * @param array|string       $replyTo
     * @param array|string       $to
     * @param array|string       $cc
     * @param array|string       $cco
     * @param string|string      $subject
     * @param string|string      $body
     * @param null|string        $attachment
     * @return bool
     */
    public function enviaEmail($from, $replyTo, $to, $cc, $cco, $subject, $body, $attachment = null)
    {
        try {
            $this->mailer->Username = $from['username'];
            $this->mailer->Password = $from['password'];
            $this->mailer->Port = $from['port'];

            // Reply To
            if (!is_array($replyTo)) {
                if (!empty($replyTo)) {
                    $this->mailer->addReplyTo($replyTo);
                }
            } else {
                $email = isset($replyTo['email']) ? $replyTo['email'] : '';
                $name = isset($replyTo['name']) ? $replyTo['name'] : '';
                $this->mailer->addReplyTo($email, $name);
            }

            // From
            $this->mailer->setFrom($from['username'], $from['name']);

            // To
            if (!is_array($to)) {
                if (!empty($to)) {
                    $this->mailer->addAddress($to);
                }
            } else {
                foreach ($to as $item) {
                    $email = isset($item['email']) ? $item['email'] : '';
                    $name = isset($item['name']) ? $item['name'] : '';
                    $this->mailer->addAddress($email, $name);
                }
            }

            // CC
            if (!is_array($cc)) {
                if (!empty($cc)) {
                    $this->mailer->addCC($cc);
                }
            } else {
                foreach ($cc as $item) {
                    $email = isset($item['email']) ? $item['email'] : '';
                    $name = isset($item['name']) ? $item['name'] : '';
                    $this->mailer->addCC($email, $name);
                }
            }

            // CCO
            if (!is_array($cco)) {
                if (!empty($cco)) {
                    $this->mailer->addBCC($cco);
                }
            } else {
                foreach ($cco as $item) {
                    $email = isset($item['email']) ? $item['email'] : '';
                    $name = isset($item['name']) ? $item['name'] : '';
                    $this->mailer->addBCC($email, $name);
                }
            }

            // Attachment
            if (is_string($attachment)) {
                if (!empty($attachment)) {
                    $this->mailer->addAttachment($attachment);
                }
            } elseif(is_array($attachment)) {
                foreach ($attachment as $item) {
                    if (is_string($item)) {
                        $this->mailer->addAttachment($item);
                    }
                }
            }

            $this->mailer->Subject = UtilHelper::encodeToUtf8($subject);
            $this->mailer->msgHTML($body);

            $send = $this->mailer->send();

            $this->mailer->clearAllRecipients();
            $this->mailer->clearReplyTos();
            $this->mailer->clearAttachments();

            //echo '<pre>';var_dump([$from, $replyTo, $to, $cc, $cc, $this->mailer->ErrorInfo]);echo '</pre>';

            return $send;
        } catch (Exception $ex) {
            return false;
        }
    }
}
