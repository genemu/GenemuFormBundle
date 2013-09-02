<?php

/*
 * This file is part of the GenemuFormBundle package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerInterface;
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
        $configs = $this->processConfiguration(new Configuration(), $configs);

        $loader->load('twig.xml');
        $loader->load('imagine.xml');
        $loader->load('form.xml');
        $loader->load('model.xml');
        $loader->load('jquery.xml');

        if (!empty($configs['autocompleter']['doctrine']) ||
            !empty($configs['tokeninput']['doctrine'])
        ) {
            $loader->load('entity.xml');
        }

        if (!empty($configs['autocompleter']['mongodb']) ||
            !empty($configs['tokeninput']['mongodb'])
        ) {
            $loader->load('mongodb.xml');
        }

        foreach (array('captcha', 'recaptcha', 'tinymce', 'date', 'file', 'image', 'autocomplete', 'select2') as $type) {
            if (isset($configs[$type]) && !empty($configs[$type]['enabled'])) {
                $method = 'register' . ucfirst($type) . 'Configuration';

                $this->$method($configs[$type], $container);
            }
        }

        $this->loadExtendedTypes('genemu.form.jquery.type.chosen', 'jquerychosen', $container);
        $this->loadExtendedTypes('genemu.form.jquery.type.autocompleter', 'jqueryautocompleter', $container);
        $this->loadExtendedTypes('genemu.form.jquery.type.tokeninput', 'jquerytokeninput', $container);
    }

    /**
     * Loads Captcha configuration
     *
     * @param array            $config    A configuration array
     * @param ContainerBuilder $container A ContainerBuilder instance
     */
    private function registerCaptchaConfiguration(array $configs, ContainerBuilder $container)
    {
        $fontDir = $container->getParameterBag()->resolveValue($configs['font_dir']);
        foreach ($configs['fonts'] as $index => $font) {
            if (is_file($fontDir . '/' . $font)) {
                $configs['fonts'][$index] = $fontDir . '/' . $font;
            } else {
                unset($configs['fonts'][$index]);
            }
        }
        unset($configs['font_dir']);
        if (empty($configs['fonts'])) {
            unset($configs['fonts']);
        }

        $backgroundColor = preg_replace('/[^0-9A-Fa-f]/', '', $configs['background_color']);
        if (!in_array(strlen($backgroundColor), array(3, 6), true)) {
            $configs['background_color'] = 'DDDDDD';
        }

        $borderColor = preg_replace('/[^0-9A-Fa-f]/', '', $configs['border_color']);
        if (!in_array(strlen($borderColor), array(3, 6), true)) {
            $configs['border_color'] = '000000';
        }

        $container->setParameter('genemu.form.captcha.options', $configs);
    }

    /**
     * Loads Recaptcha configuration
     *
     * @param array            $config    A configuration array
     * @param ContainerBuilder $container A ContainerBuilder instance
     */
    private function registerRecaptchaConfiguration(array $configs, ContainerBuilder $container)
    {
        $serverUrl = $configs['server_url'];
        if (isset($configs['ssl']['use']) && !empty($configs['ssl']['use'])) {
            $serverUrl = $configs['ssl']['server_url'];
        }

        if (empty($configs['private_key'])) {
            throw new \LogicException('Option recaptcha.private_key does not empty.');
        }

        if (empty($configs['public_key'])) {
            throw new \LogicException('Option recaptcha.public_key does not empty.');
        }

        $container->setParameter('genemu.form.recaptcha.server_url', $serverUrl);
        $container->setParameter('genemu.form.recaptcha.private_key', $configs['private_key']);
        $container->setParameter('genemu.form.recaptcha.public_key', $configs['public_key']);
        $container->setParameter('genemu.form.recaptcha.code', $configs['code']);
        $container->setParameter('genemu.form.recaptcha.options', $configs['configs']);
        $validationOptions = array_merge(array('code' => $configs['code']), $configs['validation']);
        $container->setParameter('genemu.form.recaptcha.validation.options', $validationOptions);
    }

    /**
     * Loads Tinymce configuration
     *
     * @param array            $config    A configuration array
     * @param ContainerBuilder $container A ContainerBuilder instance
     */
    private function registerTinymceConfiguration(array $configs, ContainerBuilder $container)
    {
        if (isset($configs['script_url']) && !empty($configs['script_url'])) {
            $configs['configs'] = array_merge($configs['configs'], array(
                'script_url' => $configs['script_url']
            ));
        }

        if (isset($configs['theme']) && !empty($configs['theme'])) {
            $configs['configs'] = array_merge($configs['configs'], array(
                'theme' => $configs['theme']
            ));
        }

        $container->setParameter('genemu.form.tinymce.configs', $configs['configs']);
    }

    /**
     * Loads Date configuration
     *
     * @param array            $config    A configuration array
     * @param ContainerBuilder $container A ContainerBuilder instance
     */
    private function registerDateConfiguration(array $configs, ContainerBuilder $container)
    {
        $container->setParameter('genemu.form.date.options', $configs['configs']);
    }

    /**
     * Loads File configuration
     *
     * @param array            $config    A configuration array
     * @param ContainerBuilder $container A ContainerBuilder instance
     */
    private function registerFileConfiguration(array $configs, ContainerBuilder $container)
    {
        $rootDir = $container->getParameter('genemu.form.file.root_dir');
        $rootDir = $container->getParameterBag()->resolveValue($rootDir);

        $uploadDir = $rootDir . '/' . $configs['folder'];
        if (!is_dir($uploadDir) && false === @mkdir($uploadDir, 0777, true)) {
            throw new \RuntimeException(sprintf('Could not create upload directory "%s".', $uploadDir));
        }

        $configs['configs'] = array_merge($configs['configs'], array(
            'script'    => 'genemu_upload',
            'swf'       => $configs['swf'],
            'cancelImg' => $configs['cancel_img'],
            'folder'    => $configs['folder']
        ));

        $container->setParameter('genemu.form.file.folder', $configs['folder']);
        $container->setParameter('genemu.form.file.upload_dir', $rootDir . '/' . $configs['folder']);
        $container->setParameter('genemu.form.file.options', $configs['configs']);
    }

    /**
     * Loads Image configuration
     *
     * @param array            $config    A configuration array
     * @param ContainerBuilder $container A ContainerBuilder instance
     */
    private function registerImageConfiguration(array $configs, ContainerBuilder $container)
    {
        if (empty($configs['selected'])) {
            throw new \LogicException('Your selected thumbnail does not empty.');
        }

        if (!isset($configs['thumbnails'][$configs['selected']])) {
            throw new \LogicException(sprintf('Your selected %s is not thumbnail.', $configs['selected']));
        }

        $filters = array();
        $reflection = new \ReflectionClass('Genemu\\Bundle\\FormBundle\\Gd\\File\\Image');

        foreach ($configs['filters'] as $filter) {
            if ($reflection->hasMethod('addFilter' . ucfirst($filter))) {
                $filters[] = $filter;
            }
        }

        $container->setParameter('genemu.form.image.filters', $filters);
        $container->setParameter('genemu.form.image.selected', $configs['selected']);
        $container->setParameter('genemu.form.image.thumbnails', $configs['thumbnails']);
    }

    private function registerAutocompleteConfiguration(array $configs, ContainerBuilder $container)
    {
        $serviceId = 'genemu.form.jquery.type.autocomplete';
        $textDef = new DefinitionDecorator($serviceId);
        $textDef->addArgument('text')->addTag('form.type', array('alias' => 'genemu_jqueryautocomplete_text'));
        $container->setDefinition($serviceId . '.text', $textDef);

        $doctrineDef = new DefinitionDecorator($serviceId);
        $doctrineDef
            ->addArgument('entity')
            ->addArgument(new Reference('doctrine', ContainerInterface::NULL_ON_INVALID_REFERENCE))
            ->addTag('form.type', array('alias' => 'genemu_jqueryautocomplete_entity'))
        ;
        $container->setDefinition($serviceId . '.entity', $doctrineDef);

        $mongoDef = new DefinitionDecorator($serviceId);
        $mongoDef
            ->addArgument('document')
            ->addArgument(new Reference('doctrine_mongodb', ContainerInterface::NULL_ON_INVALID_REFERENCE))
            ->addTag('form.type', array('alias' => 'genemu_jqueryautocomplete_document'))
        ;
        $container->setDefinition($serviceId . '.document', $mongoDef);


    }

    private function registerSelect2Configuration(array $configs, ContainerBuilder $container)
    {
        $serviceId = 'genemu.form.jquery.type.select2';
        foreach (array_merge($this->getChoiceTypeNames(), array('hidden')) as $type) {
            $typeDef = new DefinitionDecorator($serviceId);
            $typeDef
                ->addArgument($type)
                ->addArgument($configs['configs'])
                ->addTag('form.type', array('alias' => 'genemu_jqueryselect2_'.$type))
            ;

            $container->setDefinition($serviceId.'.'.$type, $typeDef);
        }
    }

    /**
     * Loads extended form types.
     *
     * @param string           $serviceId Id of the abstract service
     * @param string           $name      Name of the type
     * @param ContainerBuilder $container A ContainerBuilder instance
     */
    private function loadExtendedTypes($serviceId, $name, ContainerBuilder $container)
    {
        foreach ($this->getChoiceTypeNames() as $type) {
            $typeDef = new DefinitionDecorator($serviceId);
            $typeDef->addArgument($type)->addTag('form.type', array('alias' => 'genemu_'.$name.'_'.$type));

            $container->setDefinition($serviceId.'.'.$type, $typeDef);
        }
    }

    private function getChoiceTypeNames()
    {
        return array('choice', 'language', 'country', 'timezone', 'locale', 'entity', 'document', 'model', 'currency');
    }
}
