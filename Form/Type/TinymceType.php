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
 * TinymceType
 *
 * @author Olivier Chauvel <olivier@gmail.com>
 */
class TinymceType extends AbstractType
{
    protected $options;

    /**
     * Construct.
     *
     * @param string $theme
     * @param string $scriptUrl
     * @param string $width
     * @param string $height
     * @param mixed $config
     */
    public function __construct($theme, $scriptUrl, $width, $height, $config)
    {
        $this->options = array(
            'theme' => $theme,
            'script_url' => $scriptUrl,
            'width' => $width,
            'height' => $height,
            'config' => $config
        );
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        if(!$options['script_url']) {
            throw new FormException('The child node "script_url" at path "genenu_form.tinymce" must be configured.');
        }

        $builder
            ->setAttribute('theme', $options['theme'])
            ->setAttribute('script_url', $options['script_url'])
            ->setAttribute('height', $options['height'])
            ->setAttribute('width', $options['width'])
            ->setAttribute('config', $options['config']);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form)
    {
        $view
            ->set('theme', $form->getAttribute('theme'))
            ->set('script_url', $form->getAttribute('script_url'))
            ->set('height', $form->getAttribute('height'))
            ->set('width', $form->getAttribute('width'))
            ->set('config', $form->getAttribute('config'))
            ->set('culture', \Locale::getDefault());
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