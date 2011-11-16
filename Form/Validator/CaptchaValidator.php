<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Form\Validator;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormValidatorInterface;
use Symfony\Component\Form\FormError;

use Genemu\Bundle\FormBundle\Gd\GdInterface;

/**
 * CaptchaValidator
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class CaptchaValidator implements FormValidatorInterface
{
    protected $captcha;

    /**
     * Construct
     *
     * @param Captcha $captcha
     */
    public function __construct(GdInterface $captcha)
    {
        $this->captcha = $captcha;
    }

    /**
     * {@inheritdoc}
     */
    public function validate(FormInterface $form)
    {
        $code = $form->getData();
        $encoded = $this->captcha->encode($code);
        $length = $this->captcha->getLength();

        $generate = $this->captcha->getCode();

        if ($length < strlen($code)) {
            $form->addError(new FormError('"'.$code.'" must be '.$length.' characters long.'));
        }

        if ($generate != $encoded) {
            $form->addError(new FormError('The numbers you typed in are invalid.'));
        }

        $this->captcha->removeCode();
    }
}
