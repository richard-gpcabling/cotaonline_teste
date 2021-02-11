<?php

namespace App\Forms;

use App\Forms\Validator\DatabaseExistsValidator;
use App\Library\Email\EmailAutomatico;
use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\InclusionIn;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;

class EmailAutomaticoForm extends Form
{
    private $entity;

    public function initialize($entity = null, $options = null)
    {
        $this->entity = $entity;

        $this->setEntity($entity);

        $this->setId();
        $this->setNome();
        $this->setRemetente();
        $this->setReplyTo();
        $this->setCC();
        $this->setCCO();
        $this->setGatilho();
        $this->setAssunto();
        $this->setMensagem();
        $this->setUsuarioRecebe();
        $this->setStatus();
    }

    private function setId()
    {
        $id = new Hidden('id');

        if ($this->entity->id > 0) {
            $optValidator = ['table' => 'email_automatico', 'column' => 'id', 'message' => 'Registro inválido'];
            $validator = new DatabaseExistsValidator($optValidator);
            $id->addValidator($validator);
        }

        $this->add($id);
    }

    private function setNome()
    {
        $validator = new StringLength([
            'min' => 1,
            'max' => 50,
            'messageMinimum' => 'O nome é obrigatório',
            'messageMaximum' => 'Preencha no máximo 50 caracteres'
        ]);

        $nome = new Text('nome', [
            'class' => 'form-control',
            'placeholder' => 'Exemplo: Cadastro de usuário'
        ]);
        $nome->setLabel('Nome');
        $nome->addValidator($validator);
        $nome->setFilters(['trim']);

        $this->add($nome);
    }

    private function setRemetente()
    {
        $validator = new PresenceOf([
            'message' => 'Remetente é obrigatório'
        ]);

        $remetente = new Select('remetente', \EmailAutomaticoRemetente::find(), [
            'using' => ['id', 'descricao'],
            'class' => 'form-control'
        ]);
        $remetente->setLabel('Remetente');
        $remetente->addValidator($validator);
        $remetente->setFilters(['trim']);

        $this->add($remetente);
    }

    private function setCC()
    {
        $cc = new Hidden('cc');
        $cc->setLabel('CC');
        $cc->setFilters(['trim']);

        $this->add($cc);
    }

    private function setCCO()
    {
        $cco = new Hidden('cco');
        $cco->setLabel('CCO');
        $cco->setFilters(['trim']);

        $this->add($cco);
    }

    private function setReplyTo()
    {
        $validator = new Email([
            'message' => 'O campo Responder para é obrigatório',
            'allowEmpty' => true
        ]);

        $reply_to = new Text('reply_to', ['class' => 'form-control', 'placeholder' => 'Exemplo: contato@gpcabling.com.br']);
        $reply_to->setLabel('Responder para');
        $reply_to->addValidator($validator);
        $reply_to->setFilters(['trim']);

        $this->add($reply_to);
    }

    private function setGatilho()
    {
        $options = []; $inclusionIn = [];

        $emailAutomatico = new EmailAutomatico();
        foreach ($emailAutomatico->getGatilhos() as $gatilho) {
            $options[$gatilho['id']] = $gatilho['nome'];
            $inclusionIn[] = $gatilho['id'];
        }

        $validator = new InclusionIn([
            'message' => 'Gatilho inválido',
            'domain' => $inclusionIn
        ]);

        $gatilhos = new Select('gatilho', $options, ['class' => 'form-control']);
        $gatilhos->setLabel('Gatilho');
        $gatilhos->addValidator($validator);

        $this->add($gatilhos);
    }

    private function setAssunto()
    {
        $validator = new PresenceOf([
            'message' => 'O assunto é obrigatório'
        ]);

        $assunto = new Text('assunto', ['class' => 'form-control', 'placeholder' => 'Exemplo: Bem vindo, {{usuario}}']);
        $assunto->setLabel('Assunto');
        $assunto->addValidator($validator);
        $assunto->setFilters(['trim']);

        $this->add($assunto);
    }

    private function setMensagem()
    {
        $validator = new PresenceOf([
            'message' => 'A mensagem é obrigatória'
        ]);

        $mensagem = new TextArea(
            'mensagem',
            [
                'class' => 'form-control tinymce-minimal',
                'rows' => 12
            ]);
        $mensagem->setLabel('Mensagem');
        $mensagem->addValidator($validator);
        $mensagem->setFilters(['trim']);

        $this->add($mensagem);
    }

    private function setUsuarioRecebe()
    {
        $validator = new Identical([
            'allowEmpty' => true,
            'value' => 1,
            'message' => 'Preencha corretamente'
        ]);

        $mensagem = new Check('usuario_recebe', [
            'value' => 1,
            'class' => 'custom-checkbox'
        ]);
        $mensagem->setLabel('Usuário recebe este email');
        $mensagem->addValidator($validator);

        $this->add($mensagem);
    }

    private function setStatus()
    {
        $validator = new InclusionIn([
            'message' => 'Status inválido',
            'domain' => ['active', 'inactive']
        ]);

        $status = new Select('status',
            ['active' => 'Ativo', 'inactive' => 'Inactive'],
            ['class' => 'form-control']
        );
        $status->setLabel('Status');
        $status->addValidator($validator);

        $this->add($status);
    }
}
