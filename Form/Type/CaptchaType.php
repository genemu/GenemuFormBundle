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
use Symfony\Component\HttpFoundation\Session;

use Genemu\Bundle\FormBundle\Gd\Type\Captcha;
use Genemu\Bundle\FormBundle\Form\Validator\CaptchaValidator;

/**
 * CaptchaType
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class CaptchaType extends AbstractType
{
    protected $session;
    protected $secret;
    protected $options;

    protected $captcha;

    /**
     * Construct
     *
     * @param Session $session
     * @param string  $secret
     * @param array   $options
     */
    public function __construct(Session $session, $secret, array $options)
    {
        $this->session = $session;
        $this->secret = $secret;
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        $captcha = new Captcha($this->session, $this->secret, $options);

        $builder
            ->addValidator(new CaptchaValidator($captcha))
            ->setAttribute('captcha', $captcha)
            ->setAttribute('format', $options['format']);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form)
    {
        $captcha = $form->getAttribute('captcha');

        $view
            ->set('src', $captcha->getBase64($form->getAttribute('format')))
            ->set('width', $captcha->getWidth())
            ->set('height', $captcha->getHeight());
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions(array $options)
    {
        return array_replace($this->options, $options);
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
