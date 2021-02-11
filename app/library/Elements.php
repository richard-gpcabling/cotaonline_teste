<?php

use Phalcon\Mvc\User\Component;

/**
 * Elements
 *
 * Helps to build UI elements for the application
 */
class Elements extends Component
{

    private $_headerMenu = array(
        'navbar-left' => array(
            'cliente' => array(
                'caption' => 'Clientes',
                'action' => 'index'
            ),
            'usuario' => array(
                'caption' => 'Usuários',
                'action' => 'index'
            ),
            'about' => array(
                'caption' => 'Sobre',
                'action' => 'index'
            ),
        ),
        'navbar-right' => array(
            'session' => array(
                'caption' => 'Login',
                'action' => 'index'
            ),
            '' => array(
                'caption' => 'Cadastro',
                'action' => 'register'
            ),
        )
    );

    private $_tabs = array(
        'Usuario' => array(
            'controller' => 'usuario',
            'action' => 'index',
            'any' => false
        ),
        'Your Profile' => array(
            'controller' => 'invoices',
            'action' => 'profile',
            'any' => false
        )
    );

    /**
     * Builds header menu with left and right items
     *
     * @return string
     */
    public function getMenu()
    {

        $auth = $this->session->get('auth');
        if ($auth) {
            $this->_headerMenu['navbar-right']['session'] = array(
                'caption' => 'Log Out',
                'action' => 'end'
            );
            unset($this->_headerMenu['navbar-right']['']);
        } else {
            unset($this->_headerMenu['navbar-left']['usuario']);
            unset($this->_headerMenu['navbar-left']['cliente']);
        }

        $controllerName = $this->view->getControllerName();
        foreach ($this->_headerMenu as $position => $menu) {
            echo '<div class="nav-collapse">';
            echo '<ul class="nav navbar-nav ', $position, '">';
            foreach ($menu as $controller => $option) {
                if ($controllerName == $controller) {
                    echo '<li class="active">';
                } else {
                    echo '<li>';
                }
                if ($controller == 'session') {
                    echo '<form action="/'.$controller . '/' . $option['action'].'"><button class="btn btn-success navbar-btn">';
                    // echo $this->tag->linkTo($controller . '/' . $option['action'], $option['caption']);
                    echo $option['caption'];
                    echo '</button></form>';
                } elseif ($controller == '') {
                    echo '<form action="'.$controller . '/' . $option['action'].'"><button class="btn btn-primary navbar-btn">';
                    // echo $this->tag->linkTo($controller . '/' . $option['action'], $option['caption']);
                    echo $option['caption'];
                    echo '</button></form>';
                } else {
                    echo $this->tag->linkTo($controller . '/' . $option['action'], $option['caption']);
                }
                echo '</li>';
            }
            echo '</ul>';
            echo '</div>';
        }

    }

    /**
     * Returns menu tabs
     */
    public function getTabs()
    {
        $controllerName = $this->view->getControllerName();
        $actionName = $this->view->getActionName();
        echo '<ul class="nav nav-tabs">';
        foreach ($this->_tabs as $caption => $option) {
            if ($option['controller'] == $controllerName && ($option['action'] == $actionName || $option['any'])) {
                echo '<li class="active">';
            } else {
                echo '<li>';
            }
            echo $this->tag->linkTo($option['controller'] . '/' . $option['action'], $caption), '</li>';
        }
        echo '</ul>';
    }
}
