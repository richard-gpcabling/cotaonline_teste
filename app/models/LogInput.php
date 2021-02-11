<?php

class LogInput extends \Phalcon\Mvc\Model
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
    public $unique_id;

    /**
     *
     * @var string
     */
    public $nome_do_script;

    /**
     *
     * @var string
     */
    public $tabela_registro;

    /**
     *
     * @var string
     */
    public $passo;

    /**
     *
     * @var string
     */
    public $data_inicio;

    /**
     *
     * @var string
     */
    public $data_termino;

    /**
     *
     * @var string
     */
    public $servidor_de_origem;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("_log_input");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return '_log_input';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return LogInput[]|LogInput|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return LogInput|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
