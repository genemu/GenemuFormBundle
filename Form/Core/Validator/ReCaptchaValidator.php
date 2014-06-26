<?php

/*
 * This file is part of the GenemuFormBundle package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Form\Core\Validator;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Exception\InvalidConfigurationException;
use Symfony\Component\HttpFoundation\Request;

/**
 * ReCaptchaValidator
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class ReCaptchaValidator implements EventSubscriberInterface
{
    private $httpRequest;
    private $request;
    private $privateKey;
    private $options;

    /**
     * @param Request      $request
     * @param string       $privateKey
     * @param array        $options    Validation options
     */
    public function __construct(Request $request, $privateKey, array $options = array())
    {
        $this->options = $options;
        $this->request = $request;

        if (empty($options['code'])) {
            if (empty($privateKey)) {
                throw new InvalidConfigurationException('The child node "private_key" at path "genenu_form.recaptcha" must be configured.');
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
    }

    public function addOptions(array $options)
    {
        $this->options = array_merge($this->options, $options);
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function validate(FormEvent $event)
    {
        $form = $event->getForm();

        $error = '';
        $request = $this->request->request;

        $datas = array(
            'privatekey' => $this->privateKey,
            'challenge' => $request->get('recaptcha_challenge_field'),
            'response' => $request->get('recaptcha_response_field'),
            'remoteip' => $this->request->getClientIp()
        );

        if (empty($this->options['code'])) {
            if (empty($datas['challenge']) || empty($datas['response'])) {
                $error = 'The captcha is not valid.';
            } elseif (true !== ($answer = $this->check($datas, $form->getConfig()->getAttribute('option_validator')))) {
                $error = sprintf('Unable to check the captcha from the server. (%s)', $answer);
            }
        } elseif ($this->options['code'] != $datas['response']) {
            $error = "The captcha is not valid.";
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
        $options = array_merge($this->options, $options);
        $response = '';
        $datas = http_build_query($datas, null, '&');
        $httpRequest = sprintf($this->httpRequest, $options['path'], $options['host'], strlen($datas), $datas);

        $errno = 0;
        $errstr = '';
        if (false === ($fs = @fsockopen(
            empty($options['proxy']) ? $options['host'] : $options['proxy']['host'],
            empty($options['proxy']) ? $options['port'] : $options['proxy']['port'],
            $errno,
            $errstr,
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

    public static function getSubscribedEvents()
    {
        return array(FormEvents::POST_BIND => 'validate');
    }
}
