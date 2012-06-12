<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Form\Core\Validator;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormValidatorInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\Exception\FormException;
use Symfony\Component\HttpFoundation\Request;

/**
 * ReCaptchaValidator
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class ReCaptchaValidator implements FormValidatorInterface
{
    private $httpRequest;
    private $request;
    private $privateKey;

    /**
     * Constructs
     *
     * @param Request $request
     * @param string  $privateKey
     */
    public function __construct(Request $request, $privateKey)
    {
        if (empty($privateKey)) {
            throw new FormException('The child node "private_key" at path "genenu_form.captcha" must be configured.');
        }

        $this->request = $request;
        $this->privateKey = $privateKey;

        $this->httpRequest = array(
            'POST %s HTTP/1.0',
            'Host: %s',
            'Content-Type: application/x-www-form-urlencoded',
            'Content-Length: %d',
            'User-Agent: reCAPTCHA/PHP'
        );
        $this->httpRequest = implode("\r\n", $this->httpRequest)."\r\n\r\n%s";
    }

    /**
     * {@inheritdoc}
     */
    public function validate(FormInterface $form)
    {
        $error = '';
        $request = $this->request->request;
        $server = $this->request->server;

        $datas = array(
            'privatekey' => $this->privateKey,
            'challenge' => $request->get('recaptcha_challenge_field'),
            'response' => $request->get('recaptcha_response_field'),
            'remoteip' => $server->get('REMOTE_ADDR')
        );

        if (empty($datas['challenge']) || empty($datas['response'])) {
            $error = 'The captcha is not valid.';
        }

        if (true !== ($answer = $this->check($datas, $form->getAttribute('option_validator')))) {
            $error = sprintf('Unable to check the captcha from the server. (%s)', $answer);
        }

        if (!empty($error)) {
            $form->addError(new FormError($error));
        }
    }

    /**
     * Checks if the passed value is valid.
     *
     * @param array $datas   The value that should be validated
     * @param array $options The option server
     *
     * @return Boolean Whether or not the value is valid
     */
    private function check(array $datas, array $options)
    {
        $response = '';
        $datas = http_build_query($datas, null, '&');
        $httpRequest = sprintf($this->httpRequest, $options['path'], $options['host'], strlen($datas), $datas);

        if (false === ($fs = @fsockopen(
            $options['host'],
            $options['port'],
            $errno, $errstr,
            $options['timeout']
        ))) {
            return $errstr;
        }

        fwrite($fs, $httpRequest);
        while (!feof($fs)) {
            $response .= fgets($fs, 1160);
        }
        fclose($fs);

        $response = explode("\r\n\r\n", $response, 2);
        $answers = explode("\n", $response[1]);

        return 'true' === trim($answers[0]) ? true : $answers[1];
    }
}
