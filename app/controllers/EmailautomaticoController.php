<?php

use App\Services\EmailAutomaticoService;

class EmailautomaticoController extends ControllerBase
{

    public function initialize()
    {
        parent::initialize();
    }

    public function adminlistAction()
    {
        $service = new EmailAutomaticoService();

        $data = $service->getPageAdminList();
        $this->view->page = $data['page'];
        $this->view->status = $data['status'];
    }

    public function adminsaveAction($id = 0)
    {
        $service = new EmailAutomaticoService();

        if ($this->request->isPost()) {
            $result = $service->saveAdmin($this->request->getPost(), $this->session->auth['id']);
            if ($result['status'] === 'success') {
                $this->flash->success(implode('<br/>', $result['messages']));
                return $this->response->redirect('/emailautomatico/adminlist');
            } else {
                $this->flash->error(implode('<br/>', $result['messages']));
            }

            $this->view->form = $result['form'];
            $this->view->gatilhos = $result['gatilhos'];
        } else {
            $data = $service->getPageAdminSave($id);
            if (is_null($data)) {
                return $this->response->redirect('emailautomatico/adminlist');
            }

            $this->view->form = $data['form'];
            $this->view->gatilhos = $data['gatilhos'];
        }

        $this->view->id = $id;
    }

    public function adminupdatestatusAction($id, $status)
    {
        if ($this->session->auth['role'] == 'administrador') {
            EmailAutomatico::updateStatus($id, $status);
        }
        return $this->response->redirect('/emailAutomatico/adminList');
    }
}
