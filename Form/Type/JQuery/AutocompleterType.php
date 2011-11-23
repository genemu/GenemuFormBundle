<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Form\Type\JQuery;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

use Genemu\Bundle\FormBundle\Form\ChoiceList\AutocompleteArrayChoiceList;
use Genemu\Bundle\FormBundle\Form\DataTransformer\ChoiceToJsonTransformer;

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class AutocompleterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        if (!$options['choice_list']) {
            $options['choice_list'] = new AutocompleteArrayChoiceList($options['choices']);
        }

        $builder
            ->appendClientTransformer(new ChoiceToJsonTransformer(
                $options['choice_list'],
                $options['widget'],
                $options['multiple'],
                $options['ajax']
            ))
            ->setAttribute('choice_list', $options['choice_list'])
            ->setAttribute('widget', $options['widget'])
            ->setAttribute('route_name', $options['route_name']);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form)
    {
        $multiple = $form->getAttribute('multiple');
        $data = $form->getClientData();
        $data = json_decode($data, true);

        $autocompleter = '';
        if ($multiple && $data) {
            foreach ($data as $value) {
                $autocompleter .= $value['label'].', ';
            }
        } elseif (is_array($data)) {
            $autocompleter = $data['label'];
        } else {
            $autocompleter = $data;
        }

        $view
            ->set('multiple', $multiple)
            ->set('autocompleter_value', $autocompleter)
            ->set('route_name', $form->getAttribute('route_name'));
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions(array $options)
    {
        $defaultOptions = array(
            'widget'     => 'choice',
            'route_name' => null,
            'ajax'       => false
        );

        $options = array_replace($defaultOptions, $options);

        if ($options['route_name']) {
            $options['ajax'] = true;
        }

        return $options;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(array $options)
    {
        if (in_array($options['widget'], array('entity', 'document'))) {
            return 'genemu_jqueryautocompleter_'.$options['widget'];
        }

        return 'choice';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'genemu_jqueryautocompleter';
    }
}
