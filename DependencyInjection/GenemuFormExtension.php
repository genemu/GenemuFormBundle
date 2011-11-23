<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;

/**
 * GenemuFormExtension.
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class GenemuFormExtension extends Extension
{
    /**
     * Responds to the genemu_form configuration parameter.
     *
     * @param array            $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $configuration = new Configuration();
        $configs = $this->processConfiguration($configuration, $configs);

        $loader->load('twig.xml');

        $loader->load('jqueryautocompleter.xml');
        $loader->load('jqueryslider.xml');

        if (isset($configs['captcha'])) {
            $loader->load('captcha.xml');
            $this->configureCaptcha($configs['captcha'], $container);
        }

        if (isset($configs['tinymce'])) {
            $loader->load('tinymce.xml');
            $this->configureTinymce($configs['tinymce'], $container);
        }

        if (isset($configs['recaptcha'])) {
            $loader->load('recaptcha.xml');
            $this->configureRecaptcha($configs['recaptcha'], $container);
        }

        if (isset($configs['jquerydate'])) {
            $loader->load('jquerydate.xml');
            $this->configureJQueryDate($configs['jquerydate'], $container);
        }

        if (isset($configs['jqueryfile'])) {
            $loader->load('jqueryfile.xml');
            $this->configureJQueryFile($configs['jqueryfile'], $container);
        }

        if (isset($configs['jqueryimage'])) {
            $loader->load('jqueryimage.xml');
            $this->configureJQueryImage($configs['jqueryimage'], $container);
        }
    }

    /**
     * Configure Captcha
     *
     * @param array            $configs
     * @param ContainerBuilder $container
     */
    protected function configureCaptcha(array $configs, ContainerBuilder $container)
    {
        if (!in_array($configs['format'], array('jpeg', 'png', 'gif'))) {
            throw new \Exception('Format does not supprt. Choice (jpeg, png or gif)');
        }

        if (!function_exists('image'.$configs['format'])) {
            throw new \Exception('Format does not support.');
        }

        $container->setParameter('genemu.form.captcha.options', $configs);
    }

    /**
     * Configure Tinymce
     *
     * @param array            $configs
     * @param ContainerBuilder $container
     */
    protected function configureTinymce(array $configs, ContainerBuilder $container)
    {
        $options = isset($configs['configs'])?$configs['configs']:array();

        $container->setParameter('genemu.form.tinymce.theme', $configs['theme']);
        $container->setParameter('genemu.form.tinymce.script_url', $configs['script_url']);
        $container->setParameter('genemu.form.tinymce.options', $options);
    }

    /**
     * Configure Recaptcha
     *
     * @param array            $configs
     * @param ContainerBuilder $container
     */
    protected function configureRecaptcha(array $configs, ContainerBuilder $container)
    {
        $options = isset($configs['options'])?$configs['options']:array();

        $options = array_merge($options, array(
            'theme' => $configs['theme'],
            'use_ssl' => $configs['use_ssl'],
            'server_url' => $configs['server_url'],
            'server_url_ssl' => $configs['server_url_ssl']
        ));

        $container->setParameter('genemu.form.recaptcha.private_key', $configs['private_key']);
        $container->setParameter('genemu.form.recaptcha.public_key', $configs['public_key']);
        $container->setParameter('genemu.form.recaptcha.options', $options);
    }

    /**
     * Configure JQueryDate
     *
     * @param array            $configs
     * @param ContainerBuilder $container
     */
    protected function configureJQueryDate(array $configs, ContainerBuilder $container)
    {
        $options = isset($configs['configs'])?$configs['configs']:array();

        $container->setParameter('genemu.form.jquerydate.options', $options);
    }

    /**
     * Configure JQueryFile
     *
     * @param array            $configs
     * @param ContainerBuilder $container
     */
    protected function configureJQueryFile(array $configs, ContainerBuilder $container)
    {
        $options = isset($configs['configs'])?$configs['configs']:array();

        $options = array_merge($options, array(
            'script' => 'genemu_upload',
            'uploader' => $configs['uploader'],
            'cancelImg' => $configs['cancel_img'],
            'folder' => $configs['folder']
        ));

        $container->setParameter('genemu.form.jqueryfile.options', $options);
    }

    /**
     * Configure JQueryImage
     *
     * @param array            $configs
     * @param ContainerBuilder $container
     */
    protected function configureJQueryImage(array $configs, ContainerBuilder $container)
    {
        if (!isset($configs['thumbnails'][$configs['selected']])) {
            throw new \Exception('Selected thumbnails please.');
        }

        $filters = array_intersect(
            array('rotate', 'negate', 'bw', 'sepia', 'blur', 'crop'),
            $configs['filters']
        );

        $container->setParameter('genemu.form.jqueryimage.filters', $configs['filters']);
        $container->setParameter('genemu.form.jqueryimage.selected', $configs['selected']);
        $container->setParameter('genemu.form.jqueryimage.thumbnails', $configs['thumbnails']);
    }
}
