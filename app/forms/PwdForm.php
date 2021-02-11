<?php

namespace App\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Password;
use Phalcon\Validation\Validator\PresenceOf;

class PwdForm extends Form
{

	public function initialize($entity = null, $options = null)
	{
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