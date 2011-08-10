<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olchauvel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Validator\Constraints;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\ValidatorException;

/**
 * ReCaptchaValidator
 *
 * @author Olivier Chauvel <olivier@gmail.com>
 */
class ReCaptchaValidator extends ConstraintValidator
{
    protected $container;

    /**
     * Construct.
     *
     * @param ContainerInterface $container An ContainerInterface instance
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed      $value      The value that should be validated
     * @param Constraint $constraint The constrain for the validation
     *
     * @return Boolean Whether or not the value is valid
     */
    public function isValid($value, Constraint $constraint)
    {
        $request = $this->container->get('request');

        $privatekey = $this->container->getParameter('genemu.form.recaptcha.private_key');
        $challenge = $request->request->get('recaptcha_challenge_field');
        $response = $request->request->get('recaptcha_response_field');

        if(!$privatekey) {
            throw new ValidatorException('The child node "private_key" at path "genenu_form.captcha" must be configured.');
        }

        if(empty($challenge) || empty($response)) {
            $this->setMessage(sprintf($constraint->message, 'invalid captcha'));
            return false;
        }

        if(true !== ($answer = $this->check(array(
            'privatekey' => $privatekey,
            'remoteip' => $request->server->get('REMOTE_ADDR'),
            'challenge' => $challenge,
            'response' => $response
        ), $constraint))) {
            $this->setMessage(sprintf($constraint->message, $answer));
            return false;
        }

        return true;
    }

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed      $parameters The value that should be validated
     * @param Constraint $constraint The constrain for the validation
     *
     * @return Boolean Whether or not the value is valid
     */
    protected function check($parameters, Constraint $constraint)
    {
        $options = $constraint->getDefaultOption();

        if(false === ($fs = @fsockopen($options['server_host'], $options['server_port'], $errno, $errstr, $options['server_timeout']))) {
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
        while(!feof($fs)) {
            $response .= fgets($fs, 1160);
        }
        fclose($fs);

        $response = explode("\r\n\r\n", $response, 2);
        $answers = explode("\n", $response[1]);

        return 'true' == trim($answers[0])?true:$answers[1];
    }
}