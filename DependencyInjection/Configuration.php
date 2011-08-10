<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olchauvel@gmail.com>
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
 * @author Olivier Chauvel <olchauvel@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('genenu_form');
        
        $rootNode
            ->children()
                ->arrayNode('recaptcha')
                    ->isRequired()
                    ->children()
                        ->variableNode('use_ssl')->defaultFalse()->end()
                        ->variableNode('server_url')->defaultValue('http://api.recaptcha.net')->end()
                        ->variableNode('server_url_ssl')->defaultValue('https://api-secure.recaptcha.net')->end()
                        ->variableNode('theme')->defaultValue('clean')->end()
                        ->variableNode('public_key')->defaultNull()->end()
                        ->variableNode('private_key')->defaultNull()->end()
                    ->end()
                ->end()
                ->arrayNode('tinymce')
                    ->isRequired()
                    ->children()
                        ->variableNode('theme')->defaultValue('advanced')->end()
                        ->variableNode('width')->defaultNull()->end()
                        ->variableNode('height')->defaultNull()->end()
                        ->variableNode('script_url')->defaultNull()->end()
                        ->variableNode('config')->defaultNull()->end()
                    ->end()
                ->end()
                ->arrayNode('doublelist')
                    ->isRequired()
                    ->children()
                        ->variableNode('associated_first')->defaultTrue()->end()
                        ->variableNode('class')->defaultValue('double_list')->end()
                        ->variableNode('class_select')->defaultValue('double_list_select')->end()
                        ->variableNode('label_associated')->defaultValue('Associated')->end()
                        ->variableNode('label_unassociated')->defaultValue('Unassociated')->end()
                    ->end()
                ->end()
                ->arrayNode('jquerydate')
                    ->isRequired()
                    ->children()
                        ->variableNode('image')->defaultFalse()->end()
                        ->variableNode('config')->defaultNull()->end()
                    ->end()
                ->end()
            ->end();
        
        return $treeBuilder;
    }
}