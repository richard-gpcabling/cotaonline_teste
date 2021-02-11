<?php

class ContatoFormulario extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=11, nullable=false)
     */
    public $id;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=true)
     */
    public $nome_completo;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=true)
     */
    public $empresa;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=true)
     */
    public $cnpj_cpf;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=true)
     */
    public $e_mail;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=true)
     */
    public $telefone_comercial;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=true)
     */
    public $telefone_celular;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $mensagem;

    /**
     *
     * @var string
     * @Column(type="string", length=250, nullable=true)
     */
    public $anexo;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $newsletter;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $data_envio;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=true)
     */
    public $lida;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'contato_formulario';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ContatoFormulario[]|ContatoFormulario
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ContatoFormulario
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }
}
