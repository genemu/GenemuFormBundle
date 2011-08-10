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
use Symfony\Component\HttpFoundation\Request;
use Genemu\Bundle\FormBundle\Form\DataTransform\ReCaptchaTransform;

/**
 * ReCaptchaType
 *
 * @author Olivier Chauvel <olivier@gmail.com>
 */
class ReCaptchaType extends AbstractType
{
    protected $request;
    protected $publicKey;
    protected $options;

    /**
     * Construct.
     *
     * @param Request $request
     * @param string $theme
     * @param string $publicKey
     * @param string $useSsl
     * @param string $serverUrl
     * @param string $serverUrlSsl
     */
    public function __construct(Request $request, $theme, $publicKey, $useSsl, $serverUrl, $serverUrlSsl)
    {
        $this->request = $request;
        $this->publicKey = $publicKey;
        $this->options = array(
            'theme' => $theme,
            'use_ssl' => $useSsl,
            'server_url' => $serverUrl,
            'server_url_ssl' => $serverUrlSsl
        );
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->appendClientTransformer(new ReCaptchaTransform($this->request));
        
        $builder
            ->setAttribute('server', $this->getServerUrl($options))
            ->setAttribute('theme', $options['theme']);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form)
    {
        $key = $this->publicKey;

        if(!$this->publicKey) {
            throw new FormException('The child node "public_key" at path "genenu_form.captcha" must be configured.');
        }

        $view
            ->set('key', $this->publicKey)
            ->set('server', $form->getAttribute('server'))
            ->set('theme', $form->getAttribute('theme'))
            ->set('culture', \Locale::getDefault());
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
        return 'field';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'genemu_recaptcha';
    }

    /**
     * {@inheritdoc}
     */
    private function getServerUrl(array $options)
    {
        return $options['use_ssl']?$options['server_url_ssl']:$options['server_url'];
    }
}