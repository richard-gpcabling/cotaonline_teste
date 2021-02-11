<?php


namespace App\Services;

use Phalcon\Di;
use Phalcon\Paginator\Adapter\QueryBuilder as Paginator;

class LogSearchQueryService
{
    /**
     * @var \Phalcon\DiInterface
     */
    private $di;

    public function __construct()
    {
        $this->di = Di\FactoryDefault::getDefault();
    }

    public function getPageBusca()
    {
        /**
         * @var \Phalcon\Http\Request $request
         */
        $request = $this->di->getRequest();

        $limit = 50;
        $numberPage = $request->get('page', 'int', 1);

        $builder = $this->di->getModelsManager()
            ->createBuilder()
            ->columns('LogSearchQuery.content AS content, Usuario.id AS userid, Usuario.name AS name, Usuario.email AS email, LogSearchQuery.timestamp AS timestamp')
            ->from('LogSearchQuery')
            ->join('Usuario', 'LogSearchQuery.user_id = Usuario.id')
            ->orderBy('LogSearchQuery.id DESC');

        $paginator = new Paginator([
            'builder' => $builder,
            'page' => $numberPage,
            'limit' => $limit
        ]);

        return $paginator->getPaginate();
    }

    public function getPageTermos()
    {
        /**
         * @var \Phalcon\Http\Request $request
         */
        $request = $this->di->getRequest();

        $limit = 50;
        $numberPage = $request->get('page', 'int', 1);

        $builder = $this->di->getModelsManager()
            ->createBuilder()
            ->distinct('LogSearchQuery.content')
            ->columns([
                'LogSearchQuery.content',
                'count(LogSearchQuery.content) as count'
            ])
            ->from('LogSearchQuery')
            ->groupBy('LogSearchQuery.content')
            ->having('count(LogSearchQuery.content) > 0')
            ->orderBy('count(LogSearchQuery.content) DESC');

        $paginator = new Paginator([
            'builder' => $builder,
            'page' => $numberPage,
            'limit' => $limit
        ]);

        return $paginator->getPaginate();
    }

    public function getPageUsuarios()
    {
        /**
         * @var \Phalcon\Http\Request $request
         */
        $request = $this->di->getRequest();

        $limit = 50;
        $numberPage = $request->get('page', 'int', 1);

        $builder = $this->di->getModelsManager()
            ->createBuilder()
            ->distinct('LogSearchQuery.content')
            ->columns([
                'LogSearchQuery.user_id',
                'Usuario.name',
                'Usuario.email',
                'count(LogSearchQuery.user_id) as count'
            ])
            ->from('LogSearchQuery')
            ->join('Usuario', 'LogSearchQuery.user_id = Usuario.id')
            ->where('LogSearchQuery.user_id != 0')
            ->groupBy('LogSearchQuery.user_id')
            ->having('count(LogSearchQuery.user_id) > 0')
            ->orderBy('count(LogSearchQuery.user_id) DESC');

        $paginator = new Paginator([
            'builder' => $builder,
            'page' => $numberPage,
            'limit' => $limit
        ]);

        return $paginator->getPaginate();
    }
}