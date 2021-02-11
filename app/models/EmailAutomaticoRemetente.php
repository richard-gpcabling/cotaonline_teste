<?php

class EmailAutomaticoRemetente extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $descricao;

    /**
     *
     * @var string
     */
    public $username;

    /**
     *
     * @var string
     */
    public $password;

    /**
     *
     * @var integer
     */
    public $port;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("email_automatico_remetente");
        $this->hasMany('id', 'EmailAutomatico', 'remetente', ['alias' => 'EmailAutomatico']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'email_automatico_remetente';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return EmailAutomaticoRemetente[]|EmailAutomaticoRemetente|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return EmailAutomaticoRemetente|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }
}
