<?php

use Phalcon\Db\Column;
use Phalcon\Di;

class EmailAutomatico extends \Phalcon\Mvc\Model
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
    public $nome;

    /**
     *
     * @var string
     */
    public $remetente;

    /**
     *
     * @var string
     */
    public $reply_to;

    /**
     *
     * @var string
     */
    public $cc;

    /**
     *
     * @var string
     */
    public $cco;

    /**
     *
     * @var string
     */
    public $gatilho;

    /**
     *
     * @var string
     */
    public $assunto;

    /**
     *
     * @var string
     */
    public $mensagem;

    /**
     *
     * @var integer
     */
    public $usuario_recebe;

    /**
     *
     * @var string
     */
    public $status;

    /**
     *
     * @var string
     */
    public $updated_at;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("email_automatico");
        $this->hasOne('remetente', 'EmailAutomaticoRemetente', 'id', ['alias' => 'EmailAutomaticoRemetente']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'email_automatico';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return EmailAutomatico[]|EmailAutomatico|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return EmailAutomatico|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Get paginate
     *
     * @param int $page
     * @return stdClass
     */
    public static function getAdminList($page)
    {
        /**
         * @var \Phalcon\DiInterface $di
         */
        $di = Di\FactoryDefault::getDefault();

        /**
         * @var \Phalcon\Mvc\Model\Query\Builder $builder
         */
        $builder = $di->getModelsManager()
            ->createBuilder()
            ->from('EmailAutomatico')
            ->orderBy('EmailAutomatico.updated_at DESC, EmailAutomatico.status ASC');

        $paginator = new \Phalcon\Paginator\Adapter\QueryBuilder([
            'builder' => $builder,
            'limit' => 20,
            'page' => $page
        ]);

        return $paginator->getPaginate();
    }

    /**
     * Update status
     *
     * @param $id
     * @param $status
     * @return bool
     */
    public static function updateStatus($id, $status)
    {
        $entity = self::findFirst($id);
        if (!$entity) {
            return false;
        }
        $entity->status = $status === 'active' ? 'active' : 'inactive';
        return $entity->save();
    }

    /**
     * Get list by Gatilho and status active
     *
     * @param string $gatilho
     * @return array
     */
    public static function getListByGatilhoActive($gatilho)
    {
        $conditions = "gatilho = :gatilho: and status = :status:";
        $bind = ['gatilho' => $gatilho, 'status' => 'active'];
        $bindTypes = ['gatilho' => Column::BIND_PARAM_STR, 'status' => Column::BIND_PARAM_STR];

        /**
         * @var \Phalcon\DiInterface
         */
        $di = Di\FactoryDefault::getDefault();

        $find = $di->getModelsManager()
            ->createBuilder()
            ->columns('EmailAutomatico.*, EmailAutomaticoRemetente.*')
            ->from('EmailAutomatico')
            ->join('EmailAutomaticoRemetente', 'EmailAutomatico.remetente = EmailAutomaticoRemetente.id')
            ->where($conditions)
            ->getQuery()
            ->execute($bind, $bindTypes);

        return $find->toArray();
    }
}
