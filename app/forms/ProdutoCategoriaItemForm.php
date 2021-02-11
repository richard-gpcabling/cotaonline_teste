<?php

namespace App\Forms;

use App\Forms\Validator\DatabaseArrayExistsValidator;
use App\Forms\Validator\DatabaseExistsValidator;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Form;

class ProdutoCategoriaItemForm extends Form
{
    public function initialize($entity = null, $options = null)
    {
        $this->setEntity($entity);

        if (isset($options['add'])) {
            $this->setCodigoProduto();
            $this->setCategoria();
        } elseif (isset($options['add_admin'])) {
            $this->setCodigoProduto();
            $this->setCategories();
        }
    }

    public function setCodigoProduto()
    {
        $validator = new DatabaseExistsValidator([
            'table' => 'produto_core',
            'column' => 'codigo_produto',
            'message' => 'Produto não existe'
        ]);

        $codigo_produto = new Hidden('codigo_produto', ['maxlength' => 5, 'class' => 'form-control', 'readonly' => 'readonly']);
        $codigo_produto->setLabel('Código do Produto');
        $codigo_produto->addValidator($validator);
        $codigo_produto->setFilters(['trim']);

        $this->add($codigo_produto);
    }

    public function setCategoria()
    {
        $validator = new DatabaseExistsValidator([
            'table' => 'produto_categoria_estrutura',
            'column' => 'id',
            'message' => 'Categoria não existe'
        ]);

        $categoria = new Text('categoria');
        $categoria->setLabel('Categoria');
        $categoria->addValidator($validator);
        $categoria->setFilters(['absint']);

        $this->add($categoria);
    }

    public function setCategories()
    {
        $validator = new DatabaseArrayExistsValidator([
            'table' => 'produto_categoria_estrutura',
            'column' => 'id',
            'message' => 'Categoria não existe'
        ]);

        $categories = new Select('categories');
        $categories->setLabel('Categoria');
        $categories->addValidator($validator);
        $categories->setFilters(['absint']);

        $this->add($categories);
    }
}
