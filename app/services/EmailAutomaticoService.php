<?php


namespace App\Services;

use App\Forms\EmailAutomaticoForm;
use App\Helpers\StatusHelper;
use App\Helpers\UtilHelper;
use EmailAutomatico;
use Phalcon\Db\Column;
use Phalcon\Di;

class EmailAutomaticoService
{
    /**
     * @var \Phalcon\DiInterface|null
     */
    private $di;

    public function __construct()
    {
        $this->di = Di\FactoryDefault::getDefault();
    }

    public function getPageAdminSave($id)
    {
        if ((int) $id > 0) {
            $entity = EmailAutomatico::findFirst([
                'conditions' => 'id = :id:',
                'bind' => ['id' => $id],
                "bindTypes" => ['id' => Column::BIND_PARAM_INT]
            ]);

            if (!$entity) {
                return null;
            }
        } else {
            $entity = new EmailAutomatico();
            $entity->id = 0;
        }

        $emailLibrary = new \App\Library\Email\EmailAutomatico();

        $data['id'] = $id;
        $data['entity'] = $entity;
        $data['form'] = new EmailAutomaticoForm($entity);
        $data['gatilhos'] = $emailLibrary->getGatilhos();

        return $data;
    }

    public function saveAdmin($post, $id_usuario)
    {
        $form = new EmailAutomaticoForm();

        $library = new \App\Library\Email\EmailAutomatico();

        // Phalcon doesnot handle checkbox not checked
        $post['usuario_recebe'] = !isset($post['usuario_recebe']) ? 0 : 1;

        if (!$form->isValid($post)) {
            return [
                'status' => 'error',
                'messages' => UtilHelper::getValidatorMessages($form->getMessages()),
                'form' => $form,
                'gatilhos' => $library->getGatilhos()
            ];
        }

        $data = $this->getPostSaveData($post);

        // Edit
        if ($data['id'] > 0) {
            $entity = $this->editAdmin($data, $id_usuario);
        } else {
            $entity = $this->addAdmin($data, $id_usuario);
        }

        if ($entity->id < 1) {
            return [
                'status' => 'error',
                'messages' => ['Não foi possível salvar o email automático'],
                'form' => $form,
                'gatilhos' => $library->getGatilhos()
            ];
        }

        return ['status' => 'success', 'id' => $entity->id, 'messages' => ['Email salvo com sucesso']];
    }

    /**
     * Get post save data
     *
     * @param array $post
     * @return array
     */
    private function getPostSaveData($post)
    {
        $data = $post;

        function getEmails($post, $name) {
            $nameEmail = $name . '_email';
            $nameName = $name . '_name';
            $nameEstado = $name . '_estado';

            $response = [];
            if (isset($post[$nameEmail]) && is_array($post[$nameEmail])) {
                foreach ($post[$nameEmail] as $key => $value) {
                    $row = [];

                    $row['email'] = $value;
                    $row['name'] = isset($post[$nameName][$key]) ? $post[$nameName][$key] : '';
                    if ($post['gatilho'] === \App\Library\Email\EmailAutomatico::GT_ORCAMENTO_CRIACAO) {
                        $row['estado'] = isset($post[$nameEstado][$key]) ? $post[$nameEstado][$key] : '';
                    } else {
                        $row['estado'] = '';
                    }

                    $response[] = $row;
                }
            }
            return $response;
        }

        $data['cc'] = json_encode(getEmails($post, 'cc'));
        $data['cco'] = json_encode(getEmails($post, 'cco'));

        if (isset($data['cc_email'])) {
            unset($data['cc_email']);
        }

        if (isset($data['cc_name'])) {
            unset($data['cc_name']);
        }

        if (isset($data['cc_estado'])) {
            unset($data['cc_estado']);
        }

        if (isset($data['cco_email'])) {
            unset($data['cco_email']);
        }

        if (isset($data['cco_name'])) {
            unset($data['cco_name']);
        }

        if (isset($data['cco_estado'])) {
            unset($data['cco_estado']);
        }

        return $data;
    }

    private function addAdmin($data, $id_usuario)
    {
        $entity = new EmailAutomatico();

        $entity->id_usuario_add = $id_usuario;

        $entity->save($data);

        return $entity;
    }

    private function editAdmin($data, $id_usuario)
    {
        $entity = EmailAutomatico::findFirst($data['id']);
        if (!$entity) {
            return $entity;
        }

        $entity->id_usuario_update = $id_usuario;

        $entity->save($data);

        return $entity;
    }

    public function getPageAdminList()
    {
        /**
         * @var \Phalcon\Http\Request $request
         */
        $request = $this->di->get('request');

        $page = $request->get('page', 'int', 1);

        $data['status'] = StatusHelper::getList();

        $data['page'] = EmailAutomatico::getAdminList($page);

        return $data;
    }
}
