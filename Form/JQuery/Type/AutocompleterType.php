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
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormViewInterface;
use Symfony\Component\Form\FormInterface;

use Genemu\Bundle\FormBundle\Form\Core\ChoiceList\AjaxArrayChoiceList;
use Genemu\Bundle\FormBundle\Form\Core\DataTransformer\ChoiceToJsonTransformer;

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class AutocompleterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!$options['choice_list']) {
            $options['choice_list'] = new AjaxArrayChoiceList($options['choices'], $options['ajax']);
        }

        $builder
            ->appendClientTransformer(new ChoiceToJsonTransformer(
                $options['choice_list'],
                $options['widget'],
                $options['multiple'],
                $options['ajax'],
                $options['freeValues']
            ))
            ->setAttribute('choice_list', $options['choice_list'])
            ->setAttribute('widget', $options['widget'])
            ->setAttribute('route_name', $options['route_name'])
            ->setAttribute('freeValues', $options['freeValues']);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormViewInterface $view, FormInterface $form, array $options)
    {
        $datas = json_decode($form->getClientData(), true);
        $value = '';

        if (!empty($datas)) {
            if ($form->getAttribute('multiple')) {
                foreach ($datas as $data) {
                    $value .= $data['label'] . ', ';
                }
            } else {
                $value = $datas['label'];
            }
        }

        $view
            ->set('autocompleter_value', $value)
            ->set('route_name', $form->getAttribute('route_name'))
            ->set('freeValues', $form->getAttribute('freeValues'));
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions()
    {
        $options = array(
            'widget' => 'choice',
            'route_name' => null,
            'ajax' => function (Options $options, $previousValue) {
                if (null === $previousValue) {
                    if (!empty($options['route_name']))
                    {
                        return true;
                    }
                }

                return false;
            },
            'freeValues' => false,
        );

        return $options;
    }

    /**
     * {@inheritdoc}
     */
    public function getAllowedOptionValues()
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
    public function getParent()
    {
        if (in_array($options['widget'], array('entity', 'document', 'model'), true)) {
            return 'genemu_ajax' . $options['widget'];
        }

        return $options['widget'];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'genemu_jqueryautocompleter';
    }
}
