<?php

namespace App\Forms\Validator;

use Phalcon\Di;
use Phalcon\Validation;
use Phalcon\Validation\Message;
use Phalcon\Validation\Validator;

/**
 * Class DatabaseExists
 * @package App\Forms\Validator
 */
class DatabaseExistsValidator extends Validator
{
    /**
     * @var \Phalcon\Db\Adapter\Pdo $db
     */
    private $db;

    /**
     * @var \Phalcon\Validation
     */
    private $validator;

    /**
     * @var string
     */
    private $attribute;

    /**
     * @var string
     */
    private $table;

    /**
     * @var string
     */
    private $column;

    /**
     * @var string
     */
    private $message;

    /**
     * DatabaseArrayExists constructor.
     * @param array|null $options
     */
    public function __construct(array $options = null)
    {
        parent::__construct($options);
        $this->setOption("cancelOnFail", true);
        $this->db = Di\FactoryDefault::getDefault()->get('db');
    }

    /**
     * Executes the validation
     * @param \Phalcon\Validation $validator
     * @param string              $attribute
     * @return bool
     */
    public function validate(Validation $validator, $attribute)
    {
        $this->validator = $validator;
        $this->attribute = $attribute;
        $this->table = $this->getOption('table');
        $this->column = $this->getOption('column');
        $this->message = $this->getOption('message');

        $value = $this->validator->getValue($this->attribute);

        if (!$this->registerExists($value)) {
            $this->appendMessage();
            return false;
        }

        return true;
    }

    /**
     * Verify if register exists on database
     *
     * @param string|int $value
     * @return bool
     */
    private function registerExists($value)
    {
        if (is_numeric($value)) {
            $whereValue = $value;
        } elseif (is_string($value)) {
            $whereValue = "\"$value\"";
        } else {
            $whereValue = md5(time());
        }

        $query = "SELECT COUNT(*) AS total FROM {$this->table} WHERE {$this->column} = $whereValue";

        $count = $this->db->fetchColumn($query);

        return (int) $count > 0;
    }

    /**
     * Append message to Validator
     *
     * @return void
     */
    private function appendMessage()
    {
        $this->validator->appendMessage(new Message($this->message, $this->attribute, 'exists'));
    }
}
