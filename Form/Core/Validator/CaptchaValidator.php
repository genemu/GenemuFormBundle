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

use Genemu\Bundle\FormBundle\Gd\Type\Captcha;

/**
 * CaptchaValidator
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class CaptchaValidator implements FormValidatorInterface
{
    private $captcha;

    /**
     * Constructs
     *
     * @param Captcha $captcha
     */
    public function __construct(Captcha $captcha)
    {
        $this->captcha = $captcha;
    }

    /**
     * {@inheritdoc}
     */
    public function validate(FormInterface $form)
    {
        $data = $form->getData();

        if (
            $this->captcha->getLength() !== strlen($data) ||
            $this->captcha->getCode() !== $this->captcha->encode($data)
        ) {
            $form->addError(new FormError('The captcha is invalid'));
        }

        $this->captcha->removeCode();
    }
}
