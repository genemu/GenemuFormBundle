<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

use Genemu\Bundle\FormBundle\Gd\Captcha;
use Genemu\Bundle\FormBundle\Form\Validator\CaptchaValidator;

/**
 * CaptchaType
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class CaptchaType extends AbstractType
{
    protected $captcha;

    /**
     * Construct
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
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->addValidator(new CaptchaValidator($this->captcha));
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form)
    {
        $view
            ->set('src', $this->captcha->getSrc())
            ->set('width', $this->captcha->getWidth())
            ->set('height', $this->captcha->getHeight());
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions(array $options)
    {
        $defaultOptions = array(
            'width' => $this->captcha->getWidth(),
            'height' => $this->captcha->getHeight(),
            'length' => $this->captcha->getLength()
        );

        $options = array_replace($defaultOptions, $options);

        $this->captcha->setWidth($options['width']);
        $this->captcha->setHeight($options['height']);
        $this->captcha->setLength($options['length']);

        return $options;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(array $options)
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
