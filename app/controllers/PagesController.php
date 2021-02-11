<?php

class PagesController extends ControllerBase
{
    public function initialize()
    {
        $this->tag->setTitle('Sobre nós');
        parent::initialize();
    }

    public function indexAction($uri=null)
    {
    if($uri==null):
        $this->flash->error("Página inexistente.");
    return $this->dispatcher->forward(["controller" => "index","action"  => "index",]);
    else:
        $find_page = Pages::findFirstByUri($uri);
        if (!$find_page):
            $this->flash->error("Página inexistente.");
            return $this->dispatcher->forward(["controller" => "index","action"  => "index",]);
        else:
            $this->view->title=$find_page->title;
            $this->view->content=$find_page->content;
        endif;
    endif;
    }

    public function statusAction(){
        if($this->session->auth['role'] != 'administrador'):
            return $this->dispatcher->forward(["controller" => "index","action"  => "index",]);
        endif;

        $status = FileStatus::find();
        $log = LogInput::find(
            [
                'order'=>'id DESC',
                'limit'=>150,
            ]
        );
        
        $this->view->status=$status;
        $this->view->log=$log;
    }
}
