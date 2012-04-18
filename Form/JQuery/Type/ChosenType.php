<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Form\JQuery\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

/**
 * ChosenType to JQueryLib
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class ChosenType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->setAttribute('allow_single_deselect', $options['allow_single_deselect'])
                ->setAttribute('route_name',            $options['route_name'])
                ->setAttribute('query_param_name',      $options['query_param_name'])
                ->setAttribute('typing_timeout',        $options['typing_timeout'])
                ->setAttribute('json_transform_func',   $options['json_transform_func']);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form)
    {
        $view->set('allow_single_deselect', $form->getAttribute('allow_single_deselect'))
             ->set('route_name',            $form->getAttribute('route_name'))
             ->set('query_param_name',      $form->getAttribute('query_param_name'))
             ->set('typing_timeout',        $form->getAttribute('typing_timeout'))
             ->set('json_transform_func',   $form->getAttribute('json_transform_func'));
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions(array $options)
    {
        $defaultOptions = array(
            'widget'                => 'choice',
            'allow_single_deselect' => true,

            // for autocomplete: symfony route name
            'route_name'			=> null,
            // for autocomplete: name of GET parameter used to send search term to given route
            'query_param_name'		=> 'term',
            // for autocomplete: timeout used to 'intelligently' determine when to attempt an AJAX search query
            'typing_timeout'		=> 400,
            // for autocomplete: javascript function that is used to transform JSON data returned by requests to the
            //                   given route, this default implementation assumes that data returned is in the same format
            //                   as used by the 'jquery_autocomplete form-type' (also defined in the Bundle)
            'json_transform_func'	=> '
            function(data) {
    			var terms = {};
    			$.each(data, function (k, v) {
        			if (v.value && v.label) terms[v.value] = v.label;
        		});
    			return terms;
    		}'
        );

        return array_replace($defaultOptions, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function getAllowedOptionValues(array $options)
    {
        return array(
            'widget' => array(
                'choice',
                'language',
                'country',
                'timezone',
                'locale',
                'entity',
                'document',
                'model',
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(array $options)
    {
        return $options['widget'];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'genemu_jquerychosen';
    }
}
