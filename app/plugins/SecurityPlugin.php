<?php

use Phalcon\Acl;
use Phalcon\Acl\Role;
use Phalcon\Acl\Resource;
use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Acl\Adapter\Memory as AclList;

/**
 * SecurityPlugin
 *
 * This is the security plugin which controls that users only have access to the modules they're assigned to
 */
class SecurityPlugin extends Plugin
{
    /**
     * Returns an existing or new access control list
     *
     * @returns AclList
     */
    public function getAcl()
    {
        if (!isset($this->persistent->acl)) {
            $acl = new AclList();

            $acl->setDefaultAction(Acl::DENY);

            // Register roles
            $roles = [
                'users'  => new Role(
                    'Users',
                    'Member privileges, granted after sign in.'
                ),
                'guests' => new Role(
                    'Guests',
                    'Anyone browsing the site who is not signed in is considered to be a "Guest".'
                )
            ];

            foreach ($roles as $role) {
                $acl->addRole($role);
            }

            //Private area resources
            $privateResources = array(
                'contato'       => array('search','read','unread'),
                'cliente'       => array('index', 'admin', 'edit', 'save','resetParams','search', 'new', 'create','details'),
                'register'      => array('associate','updateClient','cnpjraw'),
                'produto'       => array('new', 'edit', 'save', 'create', 'admin','resetParams','limbo','unlink','updatetext','updatesearchtags','uploadimg','uploaddoc','listlink','produtoviewcount','logsearchquery','geraestrutura', 'categoria', 'categoriaExcluir', 'saveCategories','loguser'),
                'fabricante'    => array('index'),
                'categoria'     => array('index', 'search', 'new', 'edit', 'save', 'create'),
                'orcamento'     => array('index', 'edit','save','report','reporthome','createpdf','fix'),
                'conta'         => array('index','changepwd'),
                'cart'          => array('save','index','add', 'remove','info'),
                'dashboard'     => array('index'),
                'webtools'      => array('index'),
                'pages'         => array('status'),
                'listasprontas' => array('index','newlist','create','edit','update'),
                'usuario'       => array('index', 'search', 'new', 'edit', 'save', 'create', 'changeStatus','pendentes','meuvendedor','pendingtable'),
                'emailautomatico' => array('adminlist', 'adminsave', 'adminupdatestatus'),
                'report'        => array('index', 'fabricante'),
                'acl'           => array('index','save')
            );
            foreach ($privateResources as $resource => $actions) {
                $acl->addResource(new Resource($resource), $actions);
            }

            //Public area resources
            $publicResources = array(
                'index'         => array('index'),
                'cotaonline'    => array('index'),
                'multipledb'    => array('index'),
                'customframe'   => array('index'),
                'simuladordenobreak'    => array('index'),
                'contato'       => array('index','send','thanks'),
                'produto'       => array('index', 'search','customSearch','category','subcategory1','subcategory2','subcategory3','limbo'),
                'fabricante'    => array('index'),
                'about'         => array('index'),
                'register'      => array('index'),
                'api'           => array('index','getproduto'),
                //'cart'          => array('index','add', 'remove','info'),
                'elements'      => array('menu','header','footer'),
                'errors'        => array('show401', 'show404', 'show500'),
                'session'       => array('index', 'register', 'start', 'end','forgot','retrieve','getPendingTasks'),
                'contact'       => array('index', 'send'),
                'pages'             => array('index'),
                'rest'              => array('index','input','cyphertext','cypherput','cypherjayput'),
                'listasprontas' => array('lists','list'),
                'toolbox'       => array('index','jsontest')
            );
            foreach ($publicResources as $resource => $actions) {
                $acl->addResource(new Resource($resource), $actions);
            }

            //Grant access to public areas to both users and guests
            foreach ($roles as $role) {
                foreach ($publicResources as $resource => $actions) {
                    foreach ($actions as $action) {
                        $acl->allow($role->getName(), $resource, $action);
                    }
                }
            }

            //Grant access to private area to role Users
            foreach ($privateResources as $resource => $actions) {
                foreach ($actions as $action) {
                    $acl->allow('Users', $resource, $action);
                }
            }

            //The acl is stored in session, APC would be useful here too
            $this->persistent->acl = $acl;
        }

        return $this->persistent->acl;
    }

    /**
     * This action is executed before execute any action in the application
     *
     * @param Event $event
     * @param Dispatcher $dispatcher
     * @return bool
     */
    public function beforeDispatch(Event $event, Dispatcher $dispatcher)
    {

        $auth = $this->session->get('auth');
        if (!$auth) {
            $role = 'Guests';
        } else {
            $role = 'Users';
        }

        $controller = $dispatcher->getControllerName();
        $action = $dispatcher->getActionName();

        $acl = $this->getAcl();

        if (!$acl->isResource($controller)) {
            $dispatcher->forward([
                'controller' => 'errors',
                'action'     => 'show404'
            ]);

            return false;
        }

        $allowed = $acl->isAllowed($role, $controller, $action);
        if (!$allowed) {
            // Login Redirect
            $this->session->set('login_redirect', $this->router->getRewriteUri());

            if (!isset($auth)) { // user is not logged in, mostly for cart
                $this->flash->notice("Efetue o login para continuar.");
                $dispatcher->forward(array(
                    'controller' => 'session',
                    'action'     => 'index',
                ));
                // $this->session->destroy();
                return false;
            } else { //not allowed, or action does not exists
                $dispatcher->forward(array(
                    'controller' => 'errors',
                    'action'     => 'show401',
                ));
                // $this->session->destroy();
                return false;
            }
        }
    }
}
