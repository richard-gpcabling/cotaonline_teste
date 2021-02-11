<?php

namespace App\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Password;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;

class RegisterForm extends Form
{

    public function initialize($entity = null, $options = null)
    {
        // Name
        $name = new Text('name');
        $name->setLabel('Nome completo');
        $name->setFilters(array('striptags', 'string'));
        $name->addValidators(array(
            new PresenceOf(array(
                'message' => 'Nome obrigatório'
            ))
        ));
        $this->add($name);


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
