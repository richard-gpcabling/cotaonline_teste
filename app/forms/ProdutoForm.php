<?php

namespace App\Forms;

use App\Forms\Validator\DatabaseArrayExistsValidator;
use ProdutoFabricante;
use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

class ProdutoForm extends Form
{

    /**
     * Initialize the products form
     */
    public function initialize($entity = null, $options = array())
    {
        if (!isset($options['edit']) || isset($options['create'])) {
            $this->add(new Hidden("tipo"));
            $codigo_produto = new Text("codigo_produto");
            $codigo_produto->setLabel("codigo do produto");
            $codigo_produto->setFilters(array('striptags', 'string'));
            $codigo_produto->addValidators(array(
                 new PresenceOf(array(
                     'message' => 'Nome obrigatório'
                 ))
            ));
            $this->add($codigo_produto);

            $this->add(new Hidden("descricao_sys"));
            $descricao_sys = new Text("descricao_sys");
            $descricao_sys->setLabel("Descrição");
            $descricao_sys->setFilters(array('striptags', 'string'));
            $descricao_sys->addValidators(array(
                 new PresenceOf(array(
                     'message' => 'Descrição obrigatória'
                 ))
            ));
            $this->add($descricao_sys);

            $sigla_fabricante = new Select('sigla_fabricante', ProdutoFabricante::find(), array(
                 'using'      => array('sigla', 'sigla'),
                 'useEmpty'   => true,
                 'emptyText'  => '...',
                 'emptyValue' => ''
            ));
            $sigla_fabricante->setLabel('sigla_fabricante');
            $this->add($sigla_fabricante);

            $ref = new Text("ref");
            $ref->setLabel("Referencia");
            $ref->setFilters(array('striptags', 'string'));

            $this->add($ref);


            $observacoes_internas = new Text("observacoes_internas");
            $observacoes_internas->setLabel("Observação");
            $observacoes_internas->setFilters(array('striptags', 'string'));
            $this->add($observacoes_internas);
        }
        $this->add(new Hidden("id"));

        $values = array(
            '0' => "não",
            '1' => "sim"
        );
        $possui_st = new Select('possui_st', $values, array(
             'using'      => array('id', 'sigla'),
             'useEmpty'   => false,
             'emptyText'  => '...',
             'emptyValue' => ''
        ));
        $possui_st->addValidators(array(
             new PresenceOf(array(
                 'message' => 'possui_st é obrigatório'
             ))
        ));
        $possui_st->setLabel('possui_st*');
        $this->add($possui_st);

        $values = array(
            '0' => "inativo",
            '1' => "ativo"
        );
        $status = new Select('status', $values, array(
             'using'      => array('id', 'sigla'),
             'useEmpty'   => false,
             'emptyText'  => '...',
             'emptyValue' => ''
        ));
        $status->setLabel('status');
        $this->add($status);
    }

    public function isCategoriesValid($categories)
    {
        $validation = new Validation();

        $validator = new DatabaseArrayExistsValidator([
            'table' => 'produto_categoria_estrutura',
            'column' => 'id',
            'message' => 'Categoria não existe'
        ]);

        $validation->add('categories', $validator);

        $messages = $validation->validate(['categories' => $categories]);

        return count($messages) < 1;
    }
}
