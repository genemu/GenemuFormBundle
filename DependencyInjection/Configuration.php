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

        $this->addTinymce($rootNode);
        $this->addReCaptcha($rootNode);
        $this->addJQueryDate($rootNode);

        return $treeBuilder;
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
}
