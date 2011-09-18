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

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\ChoiceList\ArrayChoiceList;
use Genemu\Bundle\FormBundle\Form\DataTransform\JQueryAutocompleterTransform;

/**
 * JQueryAutocompleterType
 *
 * @author Olivier Chauvel <olchauvel@gmail.com>
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

            if ($builder->getAttribute('multiple')) {
                $builder->appendClientTransformer(new JQueryAutocompleterTransform());
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form)
    {
        $data = $form->getClientData();
        $choices = $form->getAttribute('choice_list')->getChoices();
        $value = '';

        if ($form->getAttribute('multiple')) {
            $data = explode(', ', $data);
            foreach ($choices as $choice) {
                if (in_array($choice['value'], $data)) {
                    $value .= $choice['label'].', ';
                }
            }
        } else {
            foreach ($choices as $choice) {
                if ($choice['value'] == $data) {
                    $value = $choice['label'];
                }
            }
        }

        $view->set('autocomplete_value', $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions(array $options)
    {
        $defaultOptions = array(
            'widget' => 'choice'
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
