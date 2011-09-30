<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olchauvel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Exception\FormException;
use Symfony\Component\Form\FormValidatorInterface;

/**
 * ReCaptchaType
 *
 * @author Olivier Chauvel <olchauvel@gmail.com>
 */
class ReCaptchaType extends AbstractType
{
    protected $validator;
    protected $publicKey;
    protected $options;

    /**
     * Construct
     *
     * @param FormValidatoInterface $validator
     * @param string                $pulicKey
     * @param array                 $options
     */
    public function __construct(FormValidatorInterface $validator, $publicKey, array $options)
    {
        $this->validator = $validator;
        $this->publicKey = $publicKey;
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        if (!$this->publicKey) {
            throw new FormException('The child node "public_key" at path "genenu_form.captcha" must be configured.');
        }

        $configs = array(
            'theme' => $options['theme'],
            'lang' => \Locale::getDefault()
        );

        $optionValidator = array(
            'server_host' => $options['server_host'],
            'server_port' => $options['server_port'],
            'server_path' => $options['server_path'],
            'server_timeout' => $options['server_timeout']
        );

        $builder
            ->addValidator($this->validator)
            ->setAttribute('option_validator', $optionValidator)
            ->setAttribute('server', $this->getServerUrl($options))
            ->setAttribute('configs', $configs);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form)
    {
        $view
            ->set('public_key', $this->publicKey)
            ->set('server', $form->getAttribute('server'))
            ->set('configs', $form->getAttribute('configs'));
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions(array $options)
    {
        $defaultOptions = array_merge(array(
            'server_host' => 'api-verify.recaptcha.net',
            'server_port' => 80,
            'server_path' => '/verify',
            'server_timeout' => 10
        ), $this->options);

        return array_replace($defaultOptions, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'genemu_recaptcha';
    }

    /**
     * Return a server url for option use_ssl
     *
     * @param array $options
     *
     * @return url server for option use_ssl
     */
    protected function getServerUrl(array $options)
    {
        return $options['use_ssl']?$options['server_url_ssl']:$options['server_url'];
    }
}
