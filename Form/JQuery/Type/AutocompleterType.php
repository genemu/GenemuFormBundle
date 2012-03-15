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
    public function buildForm(FormBuilder $builder, array $options)
    {
        if (true === empty($options['choice_list'])) {
            $options['choice_list'] = new AjaxArrayChoiceList($options['choices'], $options['ajax']);
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
            ->setAttribute('route_name', $options['route_name'])
            ->setAttribute('ids', $options['ids'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form)
    {
        $datas = json_decode($form->getClientData(), true);
        $value = '';

        if (false === empty($datas)) {
            if (true === $form->getAttribute('multiple')) {
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
            ->set('ids', $form->getAttribute('ids'))
            ->set('form_name', $form->getRoot()->getName())
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions(array $options)
    {
        $defaultOptions = array(
            'widget' => 'choice',
            'route_name' => null,
            'ajax' => false,
            'ids' => array(),
        );

        $options = array_replace($defaultOptions, $options);

        if (false === empty($options['route_name'])) {
            $options['ajax'] = true;
        }

        return $options;
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
        if (true === in_array($options['widget'], array('entity', 'document', 'model'), true)) {
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
