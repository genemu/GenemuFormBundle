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

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Exception\FormException;

class ReCaptchaType extends AbstractType
{
    private $container;
    
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->setAttribute('server', $this->getServerUrl($options))
            ->setAttribute('theme', $options['theme']);
    }
    
    public function buildView(FormView $view, FormInterface $form)
    {
        $key = $this->container->getParameter('genemu.form.recaptcha.public_key');
        
        if(!$key) {
            throw new FormException('The child node "public_key" at path "genenu_form.captcha" must be configured.');
        }
        
        $view
            ->set('key', $key)
            ->set('server', $form->getAttribute('server'))
            ->set('theme', $form->getAttribute('theme'))
            ->set('culture', \Locale::getDefault());
    }
    
    public function getDefaultOptions(array $options)
    {
        $defaultOptions = array(
            'use_ssl' => $this->container->getParameter('genemu.form.recaptcha.use_ssl'),
            'server_url' => $this->container->getParameter('genemu.form.recaptcha.server_url'),
            'server_url_ssl' => $this->container->getParameter('genemu.form.recaptcha.server_url_ssl'),
            'theme' => $this->container->getParameter('genemu.form.recaptcha.theme')
        );

        return array_replace($defaultOptions, $options);
    }
    
    public function getParent(array $options)
    {
        return 'field';
    }
    
    public function getName()
    {
        return 'genemu_recaptcha';
    }
    
    protected function getServerUrl(array $options)
    {
        return $options['use_ssl']?$options['server_url_ssl']:$options['server_url'];
    }
}