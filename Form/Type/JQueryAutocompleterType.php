<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\ChoiceList\ArrayChoiceList;
use Genemu\Bundle\FormBundle\Form\DataTransform\JQueryAutocompleterTransform;

/**
 * JQueryAutocompleterType
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class JQueryAutocompleterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        if ($options['choices'] || $options['choice_list']) {
            $choiceList = $options['choice_list']?$options['choice_list']->getChoices():$options['choices'];

            $choices = array();
            foreach ($choiceList as $value => $label) {
                $choices[] = array(
                    'label' => $label,
                    'value' => $value
                );
            }
            $choiceList = new ArrayChoiceList($choices);

            $builder->setAttribute('choice_list', $choiceList);
        }

        if (isset($options['multiple']) && $options['multiple']) {
            $builder->appendClientTransformer(new JQueryAutocompleterTransform());
        }

        $builder
            ->setAttribute('route_name', $options['route_name']);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form)
    {
        $data = $form->getClientData();
        $value = '';

        if ($form->getAttribute('multiple') && $data) {
            $data = json_decode($data);

            foreach ($data as $val) {
                $value .= $val->label.', ';
            }
        } else {
            $choices = $form->getAttribute('choice_list')->getChoices();

            if ($choices) {
                foreach ($choices as $choice) {
                    if ($choice['value'] == $data) {
                        $value = $choice['label'];
                    }
                }
            } else {
                $value = $data;
            }
        }

        $view
            ->set('autocomplete_value', $value)
            ->set('route_name', $form->getAttribute('route_name'));
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions(array $options)
    {
        $defaultOptions = array(
            'widget' => 'choice',
            'route_name' => null
        );

        return array_replace($defaultOptions, $options);
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
        return 'genemu_jqueryautocompleter';
    }
}
