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

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\Exception\FormException;

/**
 * JQueryAutocompleterType
 *
 * @author Olivier Chauvel <olivier@gmail.com>
 */
class JQueryAutocompleterType extends AbstractType
{
    protected $options;

    /**
     * Construct.
     *
     * @param string $url
     * @param string $valueCallback
     * @param string $config
     */
    public function __construct($url, $valueCallback, $config)
    {
        $this->options = array(
            'url' => $url,
            'value_callback' => $valueCallback,
            'config' => $config
        );
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        if(!$options['url']) {
            throw new FormException('The option "url" must be configured.');
        }
        
        $builder
            ->setAttribute('url', $options['url'])
            ->setAttribute('config', $options['config'])
            ->setAttribute('value_callback', $options['value_callback']);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form)
    {
        $value = $form->getClientData();
        $visibleValue = $form->getAttribute('value_callback')?call_user_func($form->getAttribute('value_callback'), $value):$value;
        
        $view
            ->set('value', $visibleValue)
            ->set('url', $form->getAttribute('url'))
            ->set('config', $form->getAttribute('config'));
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions(array $options)
    {
        return array_replace($this->options, $options);
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
        return 'genemu_jqueryautocompleter';
    }
}