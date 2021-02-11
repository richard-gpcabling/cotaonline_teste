<?php

namespace App\Library\Email;

use App\Helpers\UtilHelper;
use Cliente;
use ClienteFaturamento;
use Phalcon\Di;
use ProdutoCore;

class Orcamento
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
     * Envia
     *
     * @return array
     */
    public function enviaCadastro($id_orcamento)
    {
        $gatilhos = \EmailAutomatico::getListByGatilhoActive(EmailAutomatico::GT_ORCAMENTO_CRIACAO);
        if (count($gatilhos) < 1) {
            return ['status' => 'success', 'message' => 'Nenhum gatilho ativo para Confirmação de Cadastro'];
        }

        // Get data to email
        $data = $this->loadDataCadastro($id_orcamento);
        if (is_null($data)) {
            return ['status' => 'error', 'message' => 'Orçamento não encontrado'];
        }

        $success = true;
        foreach ($gatilhos as $gatilho) {
            $to = $data['to'];

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
                $to[] = ['name' => $data['primeiro_nome_usuario'], 'email' => $data['email_usuario']];
            } else {
                $to[] = ['name' => $from['name'], 'email' => $from['username']];
            }

            if (!empty($data['vend_email'])) {
                $replyTo = ['name' => $data['vend_name'], 'email' => $data['vend_email']];
            } else {
                $replyTo = ['name' => 'Policom', 'email' => $gatilho->emailAutomatico->reply_to];
            }

            $ccByEstado = $this->loadEmailsGatilhoByEstado($cc, $data['cliente_unidade_policom']);
            $ccoByEstado = $this->loadEmailsGatilhoByEstado($cco, $data['cliente_unidade_policom']);

            // Envia email
            $retorno = $this->emailLibrary->enviaEmail(
                $from,
                $replyTo,
                $to,
                $ccByEstado,
                $ccoByEstado,
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
     * @param int $id
     * @return null
     */
    private function loadDataCadastro($id_orcamento)
    {
        $orcamento = \Orcamento::findFirstById($id_orcamento);
        if (!$orcamento) {
            return null;
        }

        $response = [];
        $response['orcamento_id'] = $orcamento->id;
        $response['to'] = [];

        $response['orcamento_valor_total'] = $orcamento->total;

        // Orcamento Item
        $orcamento_item = '<p><b>Produtos:</b></p><table style="border: 1px solid #dcdcdc"><thead><tr><th>Quantidade</th><th>Código</th><th>Descrição</th><th>Fabricante</th></tr></thead><tbody>%s</tbody></table>';
        $orcamento_items = '';
        foreach ($orcamento->OrcamentoItem as $item) {
            $lista_prod_find = ProdutoCore::findFirstByCodigoProduto($item->codigo_produto);
            $orcamento_items .="<tr><td style='border-top:1px solid #dcdcdc;border-right:1px solid #dcdcdc'>".$item->quantidade."</td>
                <td style='border-top:1px solid #dcdcdc;border-right:1px solid #dcdcdc'>".$item->codigo_produto."</td>
                <td style='border-top:1px solid #dcdcdc;border-right:1px solid #dcdcdc'>".$lista_prod_find->descricao_sys."</td>
                <td style='border-top:1px solid #dcdcdc'>".$lista_prod_find->sigla_fabricante."</td>".
            "</tr>";
        }
        $response['lista_dos_produtos'] = sprintf($orcamento_item, $orcamento_items);

        $usuario = \Usuario::findFirstById($orcamento->usuario_id);
        if (!$usuario) {
            return null;
        }

        $response['nome_completo_usuario'] = $usuario->name;
        $response['primeiro_nome_usuario'] = UtilHelper::substrFirstOcurrence($usuario->name, ' ');
        $response['email_usuario'] = $usuario->email;

        if (isset($usuario->UsuarioTipo->UsuarioView[0]->c_p)) {
            $custo_pagina = $usuario->UsuarioTipo->UsuarioView[0]->c_p;
        } else {
            $custo_pagina = 0;
        }

        // Seller
        if (!empty($usuario->vendedor)) {
            $vendedor = \Usuario::findFirstById($usuario->vendedor);
            $response['vend_email']	= $vendedor->email;
            $response['vend_name']	= $vendedor->name;

            $response['to'][] = ['name' => $response['vend_name'], 'email' => $response['vend_email']];
        } else {
            $response['vend_email'] = '';
            $response['vend_name'] = '';
        }

        // Client
        if (!empty($usuario->codigo_cliente)) {
            $cliente_find = \Cliente::findFirstByUCode($usuario->codigo_cliente);
            $response['cliente_ucode'] = $cliente_find->uCode;
            $response['cliente_nome'] = $cliente_find->nome;
            $response['cliente_unidade_policom'] = $cliente_find->unidade_policom;

            $cliente_fat_find = \ClienteFaturamento::findFirstByUCode($usuario->codigo_cliente);
            $response['cliente_cnpj_cpf'] = $cliente_fat_find->cnpj_cpf;
        } else {
            $response['cliente_ucode'] = '';
            $response['cliente_nome'] = '';
            $response['cliente_unidade_policom'] = '';
            $response['cliente_cnpj_cpf'] = '';
        }

        // Company data
        if (!empty($response['cliente_ucode'])) {
            $response['empresa_dados'] = "<b>Seu e-mail:</b> ".$response['email_usuario']."<br>".
            "Nome da empresa: ".$response['cliente_nome']."<br>".
            "CPF ou CNPJ: " . $response['cliente_cnpj_cpf']. "<br>".
            "Código Policom: ". $response['cliente_ucode'];
        } else {
            $response['empresa_dados'] = "<b>Seu e-mail:</b> ".$response['usuario_email']."<br>".
                "Usuário não associado à empresa no momento deste orçamento.";
        }

        // Seller data
        if (!empty($response['vend_name'])) {
            $response['vendedor_dados'] = "<b>Seu vendedor segue em cópia neste e-mail</b><br>".$response['vend_name'].' - '.$response['vend_email'];
        } else {
            $response['vendedor_dados'] = "Sem vendedor. Nossa equipe irá designar um vendedor para atendê-lo.<br>";
        }

        // Budget total
        if ($response['orcamento_valor_total'] !== '0,00'
            && $custo_pagina
            && !empty($orcamento->ucode)
            && !empty($orcamento->tabela)
        ) {
            $response['orcamento_valor_total'] = "<b>Total orçamento:</b> R&#36;" . $response['orcamento_valor_total'];
        } else {
            $response['orcamento_valor_total'] = "Acesse seu painel para visualizar seu orcamento.";
        }

        return $response;
    }

    /**
     * Load emails from gatilho by estado
     *
     * @param array  $emails
     * @param string $estado
     * @return array
     */
    private function loadEmailsGatilhoByEstado($emails, $estado)
    {
        if (!is_array($emails) || count($emails) < 1) {
            return [];
        }

        $response = [];
        foreach ($emails as $email) {
            if ($email['estado'] === $estado || $email['estado'] === '*') {
                $response[] = $email;
            }
        }
        return $response;
    }
}
