<?php

namespace App\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Password;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;

class RetrieveForm extends Form
{

    public function initialize($entity = null, $options = null)
    {
        // Code
        $code = new Text('code');
        $code->setLabel('Código');
        $code->setFilters(array('striptags', 'string'));
        $code->addValidators(array(
            new PresenceOf(array(
                'message' => 'Código obrigatório'
            ))
        ));
        $this->add($code);


        // Email
        $email = new Text('email');
        $email->setLabel('E-Mail');
        $email->setFilters('email');
        $email->addValidators(array(
            new PresenceOf(array(
                'message' => 'E-mail obrigatório'
            )),
            new Email(array(
                'message' => 'E-mail inválido'
            ))
        ));
        $this->add($email);

        // Password
        $password = new Password('password');
        $password->setLabel('Senha');
        $password->addValidators(array(
            new PresenceOf(array(
                'message' => 'Senha obrigatória'
            ))
        ));
        $this->add($password);

        // Confirm Password
        $repeatPassword = new Password('repeatPassword');
        $repeatPassword->setLabel('Repeat Password');
        $repeatPassword->addValidators(array(
            new PresenceOf(array(
                'message' => 'Confirmação de senha obrigatória'
            ))
        ));
        $this->add($repeatPassword);
    }
}