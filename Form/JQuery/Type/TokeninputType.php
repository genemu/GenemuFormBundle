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
 * @author Adam Kuśmierz <adam@kusmierz.be>
 */
class TokeninputType extends AbstractType
{
    /**
     * Available options to set
     *
     * @var array
     */
    protected $_availableOptions = array(
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
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->setAttribute('route_name', $options['route_name']);

        foreach ($this->_availableOptions as $option) {
            if (isset($options[$option])) {
                $builder->setAttribute($option, $options[$option]);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form)
    {
        $datas = json_decode($form->getClientData(), true);
        $value = '';

        if (false === empty($datas)) {
            foreach ($datas as $data) {
                $value .= $data['label'] . ', ';
            }
        }

        $view
            ->set('tokeninput_value', $value)
            ->set('route_name', $form->getAttribute('route_name'));

        foreach ($this->_availableOptions as $option) {
            if ($form->hasAttribute($option)) {
                $view->set($option, $form->getAttribute($option));
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions(array $options)
    {
        $defaultOptions = array(
            'queryParam' => 'term',
            'preventDuplicates' => true,
            'tokenValue' => 'value',
            'propertyToSearch' => 'label',
            'theme' => 'facebook'
        );

        $options = array_replace($defaultOptions, $options);

        return $options;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(array $options)
    {
        return 'field';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'genemu_jquerytokeninput';
    }
}
