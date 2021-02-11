<?php

use Phalcon\Mvc\Model;
use Phalcon\Validation as Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;
use Phalcon\Validation\Validator\Uniqueness as UniquenessValidator;

class Usuario extends \Phalcon\Mvc\Model
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
     * @Column(type="string", length=70, nullable=false)
     */
    public $email;

    /**
     *
     * @var string
     * @Column(type="string", length=40, nullable=false)
     */
    public $password;

    /**
     *
     * @var string
     * @Column(type="string", length=250, nullable=true)
     */
    public $name;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $vendedor;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $created_at;

    /**
     *
     * @var string
     * @Column(type="string", length=45, nullable=true)
     */
    public $confirm_code;

    /**
     *
     * @var string
     * @Column(type="string", length=40, nullable=true)
     */
    public $status;

    public $_runValidator = true;
    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $codigo_cliente;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $cliente_contato;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $usuario_tipo;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $cliente_tipo;

    /**
     *
     * @var string
     * @Column(type="string", length=25, nullable=true)
     */
    public $cnpj_cpf_raw;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=true)
     */
    public $on_view;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=true)
     */
    public $on_view_empresa_cadastrar;
    
    /**
     * Validations and business logic
     *
     * @return boolean
     */
    public function validation() {
        if ($this->_runValidator) {
            $validator = new Validation();

            $validator->add('email', new EmailValidator([
                "message" =>'este email já esta cadastrado.'
            ]));
            $validator->add('email', new UniquenessValidator([
                'model' => $this,
                'message' => 'este email já esta cadastrado.'
            ]));

            return $this->validate($validator);
        }
    }
    public static function getUsers($quantity){
        $user = usuario::find(array(
                 'limit'=>($quantity)
            )
        )->toArray();
        return $user;
    }
    public function edit(){
        $attributes = array();
        if($this->username==null){array_push($attributes,'username');}
        if($this->password==null){array_push($attributes,'password');}
        if($this->name==null){array_push($attributes,'name');}
        if($this->email==null){array_push($attributes,'email');}
        if($this->created_at==null){array_push($attributes,'created_at');}
        if($this->status==null){array_push($attributes,'status');}
        if($this->confirm_code==null){array_push($attributes,'confirm_code');}
        $this->skipAttributes($attributes);
        $this->update();
    }
    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('id', 'Orcamento', 'usuario_id', ['alias' => 'Orcamento']);
        $this->belongsTo('usuario_tipo', '\UsuarioTipo', 'id', ['alias' => 'UsuarioTipo']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'usuario';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Usuario[]|Usuario
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Usuario
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
