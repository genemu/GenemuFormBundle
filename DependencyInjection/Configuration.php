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
        $this->addRecaptcha($rootNode);
        $this->addTinymce($rootNode);
        $this->addDate($rootNode);
        $this->addFile($rootNode);
        $this->addImage($rootNode);
        $this->addAutocompleter($rootNode);
        $this->addTokeninput($rootNode);
        $this->addAutocomplete($rootNode);
        $this->addSelect2($rootNode);

        return $treeBuilder;
    }

    /**
     * Add Configuration Captcha
     *
     * @param ArrayNodeDefinition $rootNode
     */
    private function addCaptcha(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('captcha')
                    ->canBeUnset()
                    ->addDefaultsIfNotSet()
                    ->treatNullLike(array('enabled' => true))
                    ->treatTrueLike(array('enabled' => true))
                    ->children()
                        ->booleanNode('enabled')->defaultTrue()->end()
                        ->arrayNode('configs')
                            ->prototype('array')
                            ->children()
                                ->arrayNode('chars')
                                    ->defaultValue(range(0, 9))
                                    ->beforeNormalization()
                                        ->ifTrue(function($v) { return !is_array($v); })
                                        ->then(function($v) { return str_split($v); })
                                    ->end()
                                    ->beforeNormalization()
                                        ->always()
                                        ->then(function($v) {
                                            return array_filter($v, function($v) {
                                                return ' ' !== $v && $v;
                                            });
                                        })
                                    ->end()
                                    ->prototype('scalar')->end()
                                ->end()
                                ->scalarNode('length')->defaultValue(4)->end()
                                ->scalarNode('width')->defaultValue(100)->end()
                                ->scalarNode('height')->defaultValue(40)->end()
                                ->scalarNode('format')
                                    ->defaultValue('png')
                                    ->validate()
                                        ->ifNotInArray(array('png', 'jpeg', 'gif'))
                                        ->thenInvalid('Invalid image format %s')
                                    ->end()
                                ->end()
                                ->scalarNode('font_size')->defaultValue(18)->end()
                                ->booleanNode('grayscale')->defaultFalse()->end()
                                ->scalarNode('background_color')
                                    ->defaultValue('DDDDDD')
                                    ->beforeNormalization()
                                        ->always()
                                        ->then(function($v) {
                                            $v = preg_replace('/[^0-9A-Fa-f]/', '', $v);
                                            return in_array(strlen($v), array(3, 6)) ? $v : 'DDDDDD';
                                        })
                                    ->end()
                                ->end()
                                ->scalarNode('background_stripes_number')->defaultValue(15)->end()
                                ->scalarNode('border_color')
                                    ->defaultValue('000000')
                                    ->beforeNormalization()
                                        ->always()
                                        ->then(function($v) {
                                            $v = preg_replace('/[^0-9A-Fa-f]/', '', $v);
                                            return in_array(strlen($v), array(3, 6)) ? $v : '000000';
                                        })
                                    ->end()
                                ->end()
                                ->scalarNode('border_size')->defaultValue(1)->end()
                                ->arrayNode('chars_rotate_range')
                                    ->defaultValue(array(-25, 25))
                                    ->treatNullLike(array(0, 0))
                                    ->treatFalseLike(array(0, 0))
                                    ->prototype('scalar')->end()
                                ->end()
                                ->arrayNode('chars_position_spreading_range')
                                    ->defaultValue(array(-3, 3))
                                    ->treatNullLike(array(0, 0))
                                    ->treatFalseLike(array(0, 0))
                                    ->prototype('scalar')->end()
                                ->end()
                                ->scalarNode('chars_spacing')->defaultValue(0)->end()
                                ->arrayNode('font_size_spreading_range')
                                    ->defaultValue(array(0, 3))
                                    ->treatNullLike(array(0, 0))
                                    ->treatFalseLike(array(0, 0))
                                    ->prototype('scalar')->end()
                                ->end()
                                ->arrayNode('fonts')
                                    ->defaultValue(
                                        array('akbar.ttf', 'brushcut.ttf', 'molten.ttf', 'planetbe.ttf', 'whoobub.ttf')
                                    )
                                    ->prototype('scalar')->end()
                                ->end()
                                ->arrayNode('font_color')
                                    ->defaultValue(array('252525', '8B8787', '550707', '3526E6', '88531E'))
                                    ->beforeNormalization()
                                        ->always()
                                        ->then(function($v) {
                                            return array_filter($v, function($v) {
                                                $v = preg_replace('/[^0-9A-Fa-f]/', '', $v);
                                                return in_array(strlen($v), array(3, 6));
                                            });
                                        })
                                    ->end()
                                    ->prototype('scalar')->end()
                                ->end()
                            ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    /**
     * Add configuration Recaptcha
     *
     * @param ArrayNodeDefinition $rootNode
     */
    private function addRecaptcha(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('recaptcha')
                    ->canBeUnset()
                    ->treatNullLike(array('enabled' => true))
                    ->treatTrueLike(array('enabled' => true))
                    ->children()
                        ->booleanNode('enabled')->defaultTrue()->end()
                        ->scalarNode('server_url')->defaultValue('http://api.recaptcha.net')->end()
                        ->scalarNode('public_key')->isRequired()->end()
                        ->scalarNode('private_key')->isRequired()->end()
                        ->arrayNode('ssl')
                            ->canBeUnset()
                            ->treatNullLike(array('use' => true))
                            ->treatTrueLike(array('use' => true))
                            ->children()
                                ->booleanNode('use')->defaultTrue()->end()
                                ->scalarNode('server_url')
                                    ->defaultValue('https://api-secure.recaptcha.net')
                                ->end()
                            ->end()
                        ->end()
                        ->variableNode('configs')->defaultValue(array())->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    /**
     * Add configuration Tinymce
     *
     * @param ArrayNodeDefinition $rootNode
     */
    private function addTinymce(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('tinymce')
                    ->canBeUnset()
                    ->addDefaultsIfNotSet()
                    ->treatNullLike(array('enabled' => true))
                    ->treatTrueLike(array('enabled' => true))
                    ->children()
                        ->booleanNode('enabled')->defaultTrue()->end()
                        ->scalarNode('theme')->defaultValue('advanced')->end()
                        ->scalarNode('script_url')->end()
                        ->variableNode('configs')->defaultValue(array())->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    /**
     * Add configuration Date
     *
     * @param ArrayNodeDefinition $rootNode
     */
    private function addDate(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('date')
                    ->canBeUnset()
                    ->addDefaultsIfNotSet()
                    ->treatNullLike(array('enabled' => true))
                    ->treatTrueLike(array('enabled' => true))
                    ->children()
                        ->booleanNode('enabled')->defaultTrue()->end()
                        ->variableNode('configs')->defaultValue(array())->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    /**
     * Add configuration File
     *
     * @param ArrayNodeDefinition $rootNode
     */
    private function addFile(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('file')
                    ->canBeUnset()
                    ->treatNullLike(array('enabled' => true))
                    ->treatTrueLike(array('enabled' => true))
                    ->children()
                        ->booleanNode('enabled')->defaultTrue()->end()
                        ->scalarNode('swf')->isRequired()->end()
                        ->scalarNode('cancel_img')->defaultValue('/bundles/genemuform/images/cancel.png')->end()
                        ->scalarNode('folder')->defaultValue('/upload')->end()
                        ->variableNode('configs')->defaultValue(array())->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    /**
     * Add configuration Image
     *
     * @param ArrayNodeDefinition $rootNode
     */
    private function addImage(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('image')
                    ->canBeUnset()
                    ->treatNullLike(array('enabled' => true))
                    ->treatTrueLike(array('enabled' => true))
                    ->children()
                        ->booleanNode('enabled')->defaultTrue()->end()
                        ->scalarNode('selected')->defaultValue('large')->end()
                        ->arrayNode('filters')
                            ->defaultValue(array('rotate', 'bw', 'negative', 'sepia', 'crop'))
                            ->prototype('scalar')->end()
                        ->end()
                        ->variableNode('thumbnails')
                            ->defaultValue(array(
                                'small' => array(100, 100),
                                'medium' => array(200, 200),
                                'large' => array(500, 500),
                                'extra' => array(1024, 768)
                            ))
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    /**
     * Add configuration Tokeninput
     *
     * @param ArrayNodeDefinition $rootNode
     */
    private function addTokeninput(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('tokeninput')
                    ->canBeUnset()
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('doctrine')->defaultTrue()->end()
                        ->booleanNode('mongodb')->defaultFalse()->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    /**
     * Add configuration Autocompleter
     *
     * @param ArrayNodeDefinition $rootNode
     */
    private function addAutocompleter(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('autocompleter')
                    ->canBeUnset()
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('doctrine')->defaultTrue()->end()
                        ->booleanNode('mongodb')->defaultFalse()->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    /**
     * Add configuration Autocompleter
     *
     * @param ArrayNodeDefinition $rootNode
     */
    private function addAutocomplete(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('autocomplete')
                    ->canBeUnset()
                    ->treatNullLike(array('enabled' => true))
                    ->treatTrueLike(array('enabled' => true))
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enabled')->defaultFalse()->end()
                        ->booleanNode('doctrine')->defaultTrue()->end()
                        ->booleanNode('mongodb')->defaultFalse()->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addSelect2(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('select2')
                    ->canBeUnset()
                    ->treatNullLike(array('enabled' => true))
                    ->treatTrueLike(array('enabled' => true))
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enabled')->defaultFalse()->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
