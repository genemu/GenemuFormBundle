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
use Symfony\Component\Form\FormViewInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormValidatorInterface;
use Symfony\Component\Form\Exception\FormException;

/**
 * ReCaptchaType
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class ReCaptchaType extends AbstractType
{
    private $validator;
    private $publicKey;
    private $serverUrl;
    private $options;

    /**
     * Constructs
     *
     * @param FormValidatoInterface $validator
     * @param string                $pulicKey
     * @param string                $serverUrl
     * @param array                 $options
     */
    public function __construct(FormValidatorInterface $validator, $publicKey, $serverUrl, array $options)
    {
        if (empty($publicKey)) {
            throw new FormException('The child node "public_key" at path "genenu_form.captcha" must be configured.');
        }

        $this->validator = $validator;
        $this->publicKey = $publicKey;
        $this->serverUrl = $serverUrl;
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options = $this->getDefaultOptions();

        $builder
            ->addValidator($this->validator)
            ->setAttribute('option_validator', $options['validator'])
            ->setAttribute('configs', $options['configs']);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormViewInterface $view, FormInterface $form, array $options)
    {
        $view->addVars(array(
            'public_key' => $this->publicKey,
            'server' => $this->serverUrl,
            'configs' => $form->getAttribute('configs'),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions()
    {
        return array(
            'configs' => array_merge($this->options, array(
                'lang' => \Locale::getDefault(),
            )),
            'validator' => array(
                'host' => 'api-verify.recaptcha.net',
                'port' => 80,
                'path' => '/verify',
                'timeout' => 10,
            ),
            'error_bubbling' => false,
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'genemu_recaptcha';
    }
}
