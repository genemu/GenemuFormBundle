<?php

/*
 * This file is part of the GenemuFormBundle package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Form\Core\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Genemu\Bundle\FormBundle\Form\Core\Validator\CaptchaValidator;
use Genemu\Bundle\FormBundle\Captcha\CaptchaService;

/**
 * CaptchaType
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class CaptchaType extends AbstractType
{
    /**
     * @var \Genemu\Bundle\FormBundle\Captcha\CaptchaService
     */
    private $captchaService;

    /**
     * @var \Genemu\Bundle\FormBundle\Form\Core\Validator\CaptchaValidator
     */
    private $validator;

    /**
     * @param \Genemu\Bundle\FormBundle\Captcha\CaptchaService $captchaService
     * @param \Genemu\Bundle\FormBundle\Form\Core\Validator\CaptchaValidator $validator
     */
    public function __construct(CaptchaService $captchaService, CaptchaValidator $validator)
    {
        $this->captchaService = $captchaService;
        $this->validator = $validator;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addEventSubscriber($this->validator)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $captcha = $this->captchaService->generateCaptcha($options['config_name']);

        $view->vars = array_replace($view->vars, array(
            'value'                 => '',
            'src'                   => $captcha->generate(),
            'width'                 => $captcha->getWidth(),
            'height'                => $captcha->getHeight(),
            'config_name'           => $options['config_name'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'attr'                  => array(
                'autocomplete'      => 'off',
            ),
        ));

        $resolver->setRequired(array(
            'config_name'
        ));
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
