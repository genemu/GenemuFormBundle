<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olchauvel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Form\Validator;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormValidatorInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\HttpFoundation\Request;

/**
 * ReCaptchaValidator
 *
 * @author Olivier Chauvel <olchauvel@gmail.com>
 */
class ReCaptchaValidator implements FormValidatorInterface
{
    protected $_request;
    protected $_privateKey;

    /*
     * Construct
     *
     * @param Request $request
     * @param string  $privateKey
     */
    public function __construct(Request $request, $privateKey)
    {
        $this->_request = $request;
        $this->_privateKey = $privateKey;
    }

    /*
     * {@inheritdoc}
     */
    public function validate(FormInterface $form)
    {
        if (!$this->_privateKey) {
            throw new ValidatorException('The child node "private_key" at path "genenu_form.recaptcha" must be configured.');
        }

        $request = $this->_request->request;
        $server = $this->_request->server;

        $parameters = array(
            'privatekey' => $this->_privateKey,
            'challenge' => $request->get('recaptcha_challenge_field'),
            'response' => $request->get('recaptcha_response_field'),
            'remoteip' => $server->get('REMOTE_ADDR')
        );

        if (empty($parameters['challenge']) || empty($parameters['response'])) {
            $form->addError(new FormError('The captcha is not valid.'));
        }

        if (true !== ($answer = $this->check($parameters, $form->getAttribute('option_validator')))) {
            $form->addError(new FormError(sprintf('Unable to check the captcha from the server. (%s)', $answer)));
        }
    }

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $parameters The value that should be validated
     * @param mixed $options    The option server
     *
     * @return Boolean Whether or not the value is valid
     */
    protected function check(array $parameters, array $options)
    {
        if (false === ($fs = @fsockopen($options['server_host'], $options['server_port'], $errno, $errstr, $options['server_timeout']))) {
            $this->setMessage(sprintf($constraint->serverProblem, $errorstr));
            return false;
        }

        $query = http_build_query($parameters, null, '&');

        fwrite($fs, sprintf(
            "POST %s HTTP/1.0\r\n".
            "Host: %s\r\n".
            "Content-Type: application/x-www-form-urlencoded\r\n".
            "Content-Length: %d\r\n".
            "User-Agent: reCAPTCHA/PHP/symfony\r\n".
            "\r\n%s", $options['server_path'], $options['server_host'], strlen($query), $query)
        );

        $response = '';
        while (!feof($fs)) {
            $response .= fgets($fs, 1160);
        }
        fclose($fs);

        $response = explode("\r\n\r\n", $response, 2);
        $answers = explode("\n", $response[1]);

        return 'true' == trim($answers[0])?true:$answers[1];
    }
}
