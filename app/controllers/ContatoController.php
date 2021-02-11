<?php
use Throwable;
use App\Forms\ContatoForm;
use App\Services\ContatoFormularioService;
use App\Services\ReCaptchaService;

class ContatoController extends ControllerBase
{
    /**
	 * @var ReCaptchaService
	 */
    protected $reCaptchaService;

    public function initialize()
    {
        $this->tag->setTitle('Fale conosco');
        parent::initialize();
        $this->reCaptchaService = new ReCaptchaService($this->config);
    }

    public function indexAction()
    {
        $this->view->form = new ContatoForm;
    }

    public function sendAction()
    {
        try {
            if (!$this->request->isPost()) {
                return $this->dispatcher->forward(
                    [
                        "controller" => "contato",
                        "action"	 => "index",
                    ]
                );
            }

            try {
				$this->reCaptchaService->verifyRequest($this->request);
			} catch (Throwable $th) {
                $this->flash->error($th->getMessage());

                return $this->dispatcher->forward(
                    [
                        "controller" => "contato",
                        "action"	 => "index",
                    ]
                );
			}

            $form = new ContatoForm;
            $contato = new ContatoFormulario();
            $contato->data_envio = date('Y-m-d H:i:s');
            $data = $this->request->getPost();
            if (!isset($data['newsletter'])) {
                $data['newsletter'] = 0;
            }// phalcon form cannot handle empty checkbox. This is a workaround
            if (!$form->isValid($data, $contato)) {
                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message);
                }

                return $this->dispatcher->forward(
                    [
                        "controller" => "contato",
                        "action"	 => "index",
                    ]
                );
            }
            if ($contato->save() == false) {
                foreach ($contato->getMessages() as $message) {
                    $this->flash->error($message);
                }

                return $this->dispatcher->forward(
                    [
                        "controller" => "contato",
                        "action"	 => "index",
                    ]
                );
            }

            //Envia o e-mail para o contato
            $contato = new \App\Library\Email\Contato();
            $contato->envia($data);

            $form->clear();

            $this->flash->success("mensagem enviada com sucesso.");

            return $this->dispatcher->forward(
                [
                    "controller" => "contato",
                    "action"	 => "thanks",
                ]
            );

        } catch (\Exception $e) {
            echo get_class($e), ": ", $e->getMessage(), "\n";
            echo " File=", $e->getFile(), "\n";
            echo " Line=", $e->getLine(), "\n";
            echo $e->getTraceAsString();
        }
    }

    public function thanksAction()
    {
    }

    public function searchAction()
    {
        $service = new ContatoFormularioService();
        $this->view->page = $service->getSearchPage();
    }

    public function readAction($id=null)
    {
        if (!$this->request->isPost()) {
            $mensagem =ContatoFormulario::findFirstById($id);
            $mensagem->lida=1;
            $mensagem->save();
            if ($mensagem->save() == false) {
                foreach ($mensagem->getMessages() as $message) {
                    $this->flash->error($message);
                }
            }
            if (!$mensagem) {
                $this->flash->error("Mensagem não encontrada.");

                return $this->dispatcher->forward(
                    [
                        "controller" => "contato",
                        "action"	 => "search",
                    ]
                );
            }
            $this->view->msgid=$id;
            $this->view->date=$mensagem->data_envio;
            $this->view->form = new ContatoForm($mensagem, array('read' => true));
            $this->persistent->searchParams = [];
        }
    }

    public function unreadAction($id=null)
    {
        if (!$this->request->isPost()) {
            $mensagem =ContatoFormulario::findFirstById($id);
            $mensagem->lida=0;
            $mensagem->save();
            if ($mensagem->save() == false) {
                foreach ($mensagem->getMessages() as $message) {
                    $this->flash->error($message);
                }
            }

            if (!$mensagem) {
                $this->flash->error("Mensagem não encontrada.");
            }

            return $this->dispatcher->forward(
                [
                    "controller" => "contato",
                    "action"	 => "search",
                ]
            );

            $this->persistent->searchParams = [];
        }
    }
}
