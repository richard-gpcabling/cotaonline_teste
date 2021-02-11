<?php

class MyTest extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $descricao_site;

    /**
     *
     * @var string
     */
    public $codigo_produto;

    /**
     * Initialize method for model.
    **/
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

    }

    public static function getData()
    {
        // Takes raw data from the request
        $json = file_get_contents('https://gpcabling.com.br/api/index');

        // Converts it into a PHP object
        $data = json_decode($json);

        return $data;
    }

    public static function getProdData($codigo_produto)
    {
        // Takes raw data from the request
        $json = file_get_contents('https://gpcabling.com.br/api/getproduto/'.$codigo_produto);

        // Converts it into a PHP object
        $data = json_decode($json);

        return $data;
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ClienteCore[]|ClienteCore|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ClienteCore|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
