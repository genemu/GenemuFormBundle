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

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * 
 * @author Olivier Chauvel <olivier@gmail.com>
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

    /**
     * {@inheritdoc}
     */
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