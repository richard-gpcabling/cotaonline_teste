<?php

class ProdutoTagsItem extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Column(type="integer", length=11, nullable=false)
     */
    public $id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $produto_id;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $tag_id_array;

    /**
     * Initialize method for model.
     */
    public function initialize(){
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'produto_tags_item';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ProdutoTagsItem[]|ProdutoTagsItem
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ProdutoTagsItem
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
