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
     * @var
     */
    private $captchaService;

    /**
     * @var array
     */
    private $options;

    /**
     * @param \Genemu\Bundle\FormBundle\Captcha\CaptchaService $captchaService
     * @param \Genemu\Bundle\FormBundle\Form\Core\Validator\CaptchaValidator $validator
     * @param array $options
     */
    public function __construct(CaptchaService $captchaService, CaptchaValidator $validator, array $options = array())
    {
        $this->captchaService = $captchaService;
        $this->validator = $validator;
        $this->options = $options;
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
        $captcha = $this->captchaService->createCaptcha($options['captcha_options']);

        $view->vars = array_replace($view->vars, array(
            'value' => '',
            'src' => $captcha->getBase64($options['captcha_options']['format']),
            'width' => $captcha->getWidth(),
            'height' => $captcha->getHeight(),
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
            'captcha_options'       => array_merge(array(
                'width'             => 100,
                'height'            => 30,
                'format'            => 'png',
                'background_color'  => 'DDDDDD',
                'border_color'      => '000000',
                'chars'             => range(0, 9),
                'length'            => 4,
                'font_size'         => 16,
                'font_color'        => array('252525', '8B8787', '550707', '3526E6', '88531E'),
                'grayscale'         => false,
                'fonts'             => array('akbar.ttf', 'brushcut.ttf', 'molten.ttf', 'planetbe.ttf', 'whoobub.ttf'),
            ), $this->options),
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
