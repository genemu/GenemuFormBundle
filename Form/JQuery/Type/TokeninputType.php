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
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Genemu\Bundle\FormBundle\Form\Core\ChoiceList\AjaxSimpleChoiceList;
use Genemu\Bundle\FormBundle\Form\Core\DataTransformer\ChoiceToJsonTransformer;

/**
 * @author Adam Kuśmierz <adam@kusmierz.be>
 */
class TokeninputType extends AbstractType
{
    private $widget;

    /**
     * Available options to set
     *
     * @var array
     */
    protected $_availableTokeninputOptions = array(
        'method',
        'queryParam',
        'searchDelay',
        'minChars',
        'propertyToSearch',
        'jsonContainer',
        'crossDomain',
        'prePopulate',
        'hintText',
        'noResultsText',
        'searchingText',
        'deleteText',
        'animateDropdown',
        'theme',
        'resultsFormatter',
        'tokenFormatter',
        'tokenLimit',
        'tokenDelimiter',
        'preventDuplicates',
        'tokenValue'
    );

    public function __construct($widget)
    {
        $this->widget = $widget;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (isset($options['tokenLimit']) && is_numeric($options['tokenLimit']) && $options['tokenLimit'] > 0) {
            $options['multiple'] = (1 != $options['tokenLimit']);
        }

        if (!$options['multiple']) {
            $options['tokenLimit'] = 1;
        }

        $builder
            ->addViewTransformer(new ChoiceToJsonTransformer(
                $options['choice_list'],
                $options['ajax'],
                $this->widget,
                $options['multiple']
            ))
            ->setAttribute('choice_list', $options['choice_list'])
            ->setAttribute('route_name', $options['route_name']);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $datas = json_decode($form->getClientData(), true);
        $value = '';

        if (!empty($datas)) {
            if ($options['multiple']) {
                foreach ($datas as $data) {
                    $value .= $data['label'] . ', ';
                }
            } else {
                $value = $datas['label'];
            }
        }

        $view->vars['tokeninput_value'] = $value;
        $view->vars['route_name'] = $form->getAttribute('route_name');

        foreach ($this->_availableTokeninputOptions as $option) {
            if (null !== $options[$option]) {
                $view->vars[$option] = $options[$option];
            }
        }

        array_splice(
            $view->vars['block_prefixes'],
            array_search($this->getName(), $view->vars['block_prefixes']),
            0,
            'genemu_jquerytokeninput'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {

        $defaults = array_flip($this->_availableTokeninputOptions);
        array_walk($defaults, function(&$option, $value) {
            $option = null;
        });

        $widget = $this->widget;

        $defaults = array_merge($defaults, array(
            'route_name' => null,
            'ajax' => function (Options $options, $previousValue) {
                if (null === $previousValue) {
                    if (false === empty($options['route_name'])) {
                        return true;
                    }
                }

                return false;
            },
            'choice_list' => function (Options $options, $previousValue) use ($widget) {
                if (!in_array($widget, array('entity', 'document', 'model'))) {
                    return new AjaxSimpleChoiceList($options['choices'], $options['ajax']);
                }

                return $previousValue;
            },
            'queryParam' => 'term',
            'preventDuplicates' => true,
            'tokenValue' => 'value',
            'propertyToSearch' => 'label',
            'theme' => 'facebook'
        ));

        $resolver->setDefaults($defaults);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        if (true === in_array($this->widget, array('entity', 'document', 'model'), true)) {
            return 'genemu_ajax' . $this->widget;
        }

        return $this->widget;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'genemu_jquerytokeninput_' . $this->widget;
    }
}
