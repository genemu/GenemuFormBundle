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
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormValidatorInterface;
use Symfony\Component\Form\Exception\FormException;
use Genemu\Bundle\FormBundle\Form\Core\Validator\ReCaptchaValidator;

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
     * @param ReCaptchaValidator $validator
     * @param string                $pulicKey
     * @param string                $serverUrl
     * @param array                 $options
     */
    public function __construct(ReCaptchaValidator $validator, $publicKey, $serverUrl, array $options)
    {
        if (true === empty($publicKey)) {
            throw new FormException('The child node "public_key" at path "genemu_form.recaptcha" must be configured.');
        }

        $this->validator = $validator;
        $this->publicKey = $publicKey;
        $this->serverUrl = $serverUrl;
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        $options = $this->getDefaultOptions($options);

        $this->validator->invalidMessage = $options["invalid_message"];

        $builder
            ->addValidator($this->validator)
            ->setAttribute('option_validator', $options['validator'])
            ->setAttribute('configs', $options['configs']);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form)
    {
        $view
            ->set('public_key', $this->publicKey)
            ->set('server', $this->serverUrl)
            ->set('configs', $form->getAttribute('configs'));
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions(array $options)
    {
        $defaultOptions = array(
            'configs' => array_merge($this->options, array(
                'lang' => \Locale::getDefault(),
            )),
            'validator' => array(
                'host' => 'www.google.com',
                'port' => 80,
                'path' => '/recaptcha/api/verify',
                'timeout' => 10,
            ),
            'error_bubbling' => false,
            'invalid_message' => 'The captcha is not valid.'
        );

        return array_replace_recursive($defaultOptions, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'genemu_recaptcha';
    }

    public function getParent(array $options)
    {
        return 'text';
    }
}
