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

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

/**
 * TinymceType
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class TinymceType extends AbstractType
{
    protected $options;

    /**
     * Construct
     *
     * @param string $theme
     * @param string $scriptUrl
     * @param array $configs
     */
    public function __construct($theme, $scriptUrl, array $options)
    {
        $this->options = array(
            'theme' => $theme,
            'script_url' => $scriptUrl,
            'options' => $options
        );
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        $options = array_merge(array(
            'theme' => $options['theme'],
            'script_url' => $options['script_url']
        ), $options['options']);

        if (empty($options['language'])) {
            $options['language'] = \Locale::getDefault();
        }

        $builder->setAttribute('options', $options);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form)
    {
        $view->set('options', $form->getAttribute('options'));
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions(array $options)
    {
        $defaultOptions = array_merge(array(
            'required' => false
        ), $this->options);

        return array_replace($defaultOptions, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(array $options)
    {
        return 'textarea';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'genemu_tinymce';
    }
}
