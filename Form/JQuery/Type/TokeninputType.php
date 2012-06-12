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
 * @author Adam Kuśmierz <adam@kusmierz.be>
 */
class TokeninputType extends AbstractType
{
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

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (true === empty($options['choice_list'])) {
            $options['choice_list'] = new AjaxArrayChoiceList($options['choices'], $options['ajax']);
        }

        if (isset($options['tokenLimit']) && is_numeric($options['tokenLimit']) && $options['tokenLimit'] > 0) {
            $options['multiple'] = (1 != $options['tokenLimit']);
        }

        if (!$options['multiple']) {
            $options['tokenLimit'] = 1;
        }

        $builder
            ->addViewTransformer(new ChoiceToJsonTransformer(
                $options['choice_list'],
                $options['widget'],
                $options['multiple'],
                $options['ajax']
            ))
            ->setAttribute('choice_list', $options['choice_list'])
            ->setAttribute('widget', $options['widget'])
            ->setAttribute('route_name', $options['route_name']);

        foreach ($this->_availableTokeninputOptions as $option) {
            if (isset($options[$option])) {
                $builder->setAttribute($option, $options[$option]);
            }
        }

    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormViewInterface $view, FormInterface $form, array $options)
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
            ->setVar('tokeninput_value', $value)
            ->setVar('route_name', $form->getAttribute('route_name'));

        foreach ($this->_availableTokeninputOptions as $option) {
            if ($form->hasAttribute($option)) {
                $view->set($option, $form->getAttribute($option));
            }
        }
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
                    if (false === empty($options['route_name'])) {
                        return true;
                    }
                }

                return false;
            },
            'queryParam' => 'term',
            'preventDuplicates' => true,
            'tokenValue' => 'value',
            'propertyToSearch' => 'label',
            'theme' => 'facebook'
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
        return 'genemu_jquerytokeninput';
    }
}
