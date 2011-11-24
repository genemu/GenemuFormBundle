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

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This class contains the configuration information for the bundle
 *
 * This information is solely responsible for how the different configuration
 * sections are normalized, and merged.
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('genenu_form');

        $this->addCaptcha($rootNode);
        $this->addTinymce($rootNode);
        $this->addReCaptcha($rootNode);
        $this->addJQueryDate($rootNode);
        $this->addJQueryFile($rootNode);
        $this->addJQueryImage($rootNode);
        $this->addJQueryAutocomplete($rootNode);

        return $treeBuilder;
    }

    protected function addCaptcha(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('captcha')
                ->canBeUnset()
                ->children()
                    ->scalarNode('width')->defaultValue(100)->end()
                    ->scalarNode('height')->defaultValue(30)->end()
                    ->scalarNode('length')->defaultValue(4)->end()
                    ->scalarNode('format')->defaultValue('png')->end()
                    ->scalarNode('chars')->defaultValue('0123456789')->end()
                    ->scalarNode('font_size')->defaultValue(18)->end()
                    ->variableNode('font_color')
                        ->defaultValue(array(
                            '252525',
                            '8B8787',
                            '550707',
                            '3526E6',
                            '88531E'
                        ))
                    ->end()
                    ->scalarNode('font_dir')
                        ->defaultValue('%kernel.root_dir%/../web/bundles/genemuform/fonts')
                    ->end()
                    ->variableNode('fonts')
                        ->defaultValue(array(
                            'akbar.ttf',
                            'brushcut.ttf',
                            'molten.ttf',
                            'planetbe.ttf',
                            'whoobub.ttf'
                        ))
                    ->end()
                    ->scalarNode('background_color')->defaultValue('DDDDDD')->end()
                    ->scalarNode('border_color')->defaultValue('000000')->end()
                ->end()
            ->end();
    }

    protected function addTinymce(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('tinymce')
                    ->canBeUnset()
                    ->children()
                        ->scalarNode('theme')->defaultValue('advanced')->end()
                        ->scalarNode('script_url')->isRequired()->end()
                        ->variableNode('configs')->end()
                    ->end()
                ->end()
            ->end();
    }

    protected function addReCaptcha(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('recaptcha')
                    ->canBeUnset()
                    ->children()
                        ->scalarNode('theme')->defaultValue('clean')->end()
                        ->scalarNode('public_key')->isRequired()->end()
                        ->scalarNode('private_key')->isRequired()->end()
                        ->booleanNode('use_ssl')->defaultFalse()->end()
                        ->scalarNode('server_url')->defaultValue('http://api.recaptcha.net')->end()
                        ->scalarNode('server_url_ssl')->defaultValue('https://api-secure.recaptcha.net')->end()
                        ->variableNode('options')->end()
                    ->end()
                ->end()
            ->end();
    }

    protected function addJQueryDate(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('jquerydate')
                    ->canBeUnset()
                    ->children()
                        ->variableNode('configs')->end()
                    ->end()
                ->end()
            ->end();
    }

    protected function addJQueryFile(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('jqueryfile')
                    ->canBeUnset()
                    ->children()
                        ->scalarNode('uploader')->isRequired()->end()
                        ->scalarNode('cancel_img')->isRequired()->end()
                        ->scalarNode('folder')->isRequired()->end()
                        ->variableNode('configs')->end()
                    ->end()
                ->end()
            ->end();
    }

    protected function addJQueryImage(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('jqueryimage')
                    ->canBeUnset()
                    ->children()
                        ->scalarNode('selected')->defaultValue('large')->end()
                        ->variableNode('filters')->defaultValue(array(
                            'rotate',
                            'bw',
                            'negative',
                            'sepia',
                            'crop'
                        ))->end()
                        ->variableNode('thumbnails')->defaultValue(array(
                            'small'  => array(100, 100),
                            'medium' => array(200, 200),
                            'large'  => array(500, 500),
                            'extra'  => array(1024, 768)
                        ))->end()
                    ->end()
                ->end()
            ->end();
    }

    protected function addJQueryautocomplete(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('jqueryautocompleter')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->booleanNode('mongodb')->defaultFalse()->end()
                        ->booleanNode('doctrine')->defaultTrue()->end()
                    ->end()
                ->end()
            ->end();

        return $rootNode;
    }
}
