<?php

namespace Genemu\Bundle\FormBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ReCaptcha extends Constraint
{
    public $message = 'The captcha is not valid (%s)';
    public $serverProblem = 'Unable to check the captcha from the server. (%s)';

    /**
     * {@inheritdoc}
     */
    public function getTargets()
    {
        return Constraint::PROPERTY_CONSTRAINT;
    }
    
    public function getDefaultOption()
    {
        return array(
            'server_host' => 'api-verify.recaptcha.net',
            'server_port' => 80,
            'server_path' => '/verify',
            'server_timeout' => 10
        );
    }

    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return 'genemu_validator.recaptcha';
    }
}