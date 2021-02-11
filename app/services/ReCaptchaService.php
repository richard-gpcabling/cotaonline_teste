<?php

namespace App\Services;

use Error;
use ReCaptcha\ReCaptcha;
use Phalcon\Http\Request;
use Phalcon\Config\Adapter\Ini;

class ReCaptchaService
{
  const ERROR_IS_SPAM = 'Atividade de spam identificada.';
  const ERROR_MISSING_PAYLOAD = 'Falha ao validar o reCAPTCHA. Token ou ação não identificados.';

  /**
   * O score mínimo para permitir o acesso, sendo 0 e 1 o mínimo e máximo
   * respectivamente. O valor é proporcional a probabilidade de ser humano.
   *
   * @var double
   */
  const SCORE_THRESHOLD = 0.8;

  /**
   * @var Ini
   */
  protected $config;

  public function __construct(Ini $config) {
    $this->config = $config;
  }

  /**
   * Valida as informações de reCAPTCHA no request (grecaptcha_token e
   * grecaptcha_action).
   *
   * @param Phalcon\Http\Request $request
   * @throws Error O request é inválido.
   * @return ReCaptcha\Response
   */
  public function verifyRequest(Request $request)
  {
    $recaptchaToken = $request->getPost('grecaptcha_token');
    $recaptchaAction = $request->getPost('grecaptcha_action');

    if (!$recaptchaToken || !$recaptchaAction) {
      throw new Error(self::ERROR_MISSING_PAYLOAD);
    }

    $recaptchaResponse = (
      new ReCaptcha($this->config->application->grecaptcha_secret)
    )
      ->setExpectedAction($recaptchaAction)
      ->setScoreThreshold(self::SCORE_THRESHOLD)
      ->verify($recaptchaToken);

    if (!$recaptchaResponse->isSuccess()) {
      throw new Error(self::ERROR_IS_SPAM);
    }
    return $recaptchaResponse;
  }
}