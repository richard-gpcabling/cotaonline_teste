<?php

namespace App\Forms;

use App\Forms\Validator\DatabaseArrayExistsValidator;
use App\Forms\Validator\DatabaseExistsValidator;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator\StringLength;

class ProdutoCategoriaEstruturaForm extends Form
{
    public function initialize($entity = null, $options = null)
    {
        $this->setEntity($entity);

        if (isset($options['add'])) {
            $this->setNome();
            $this->setParent();
        } elseif (isset($options['add_admin'])) {
            $this->setNome();
            $this->setCategories();
        }
    }

    public function setNome()
    {
        $validator = new StringLength([
            'min' => 1,
            'max' => 45,
            'messageMinimum' => 'O nome é obrigatório',
            'messageMaximum' => 'Preencha no máximo 45 caracteres'
        ]);

        $nome = new Text('nome', ['maxlength' => 45, 'class' => 'form-control', 'placeholder' => 'Nome da Categoria']);
        $nome->setLabel('Nome da categoria');
        $nome->addValidator($validator);
        $nome->setFilters(['trim']);

        $this->add($nome);
    }

    public function setParent()
    {
        $validator = new DatabaseExistsValidator([
            'table' => 'produto_categoria_estrutura',
            'column' => 'id',
            'message' => 'Categoria pai não existe'
        ]);

        $parent = new Text('parent');
        $parent->setLabel('Categoria Pai');
        $parent->addValidator($validator);
        $parent->setFilters(['absint']);

        $this->add($parent);
    }

    public function setCategories()
    {
        $validator = new DatabaseArrayExistsValidator([
            'table' => 'produto_categoria_estrutura',
            'column' => 'id',
            'message' => 'Categoria pai não existe'
        ]);

        $parent = new Text('categories');
        $parent->setLabel('Categoria Pai');
        $parent->addValidator($validator);
        $parent->setFilters(['absint']);

        $this->add($parent);
    }
}
