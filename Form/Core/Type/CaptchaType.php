<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Form\Core\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormViewInterface;
use Symfony\Component\Form\FormInterface;

use Genemu\Bundle\FormBundle\Gd\Type\Captcha;
use Genemu\Bundle\FormBundle\Form\Core\Validator\CaptchaValidator;

/**
 * CaptchaType
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class CaptchaType extends AbstractType
{
    private $captcha;
    private $options;

    /**
     * Constructs
     *
     * @param Captcha $captcha
     * @param array   $options
     */
    public function __construct(Captcha $captcha, array $options)
    {
        $this->captcha = $captcha;
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->captcha->setOptions($options);

        $builder
            ->addValidator(new CaptchaValidator($this->captcha))
            ->setAttribute('captcha', $this->captcha)
            ->setAttribute('format', $options['format'])
            ->setAttribute('position', $options['position']);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormViewInterface $view, FormInterface $form, array $options)
    {
        $captcha = $form->getAttribute('captcha');

        $view->addVars(array(
            'value' => '',
            'position' => $form->getAttribute('position'),
            'src' => $captcha->getBase64($form->getAttribute('format')),
            'width' => $captcha->getWidth(),
            'height' => $captcha->getHeight(),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions()
    {
        return array_merge(array(
            'attr' => array(
                'autocomplete' => 'off'
            )
        ), $this->options);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'text';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'genemu_captcha';
    }
}
