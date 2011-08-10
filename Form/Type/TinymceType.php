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

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

/**
 * TinymceType
 *
 * @author Olivier Chauvel <olivier@gmail.com>
 */
class TinymceType extends AbstractType
{
    private $container;

    /**
     * Construct.
     *
     * @param ContainerBuilder $container A ContainerBuilder instance
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
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
        $defaultOptions = array(
            'theme' => $this->container->getParameter('genemu.form.tinymce.theme'),
            'script_url' => $this->container->getParameter('genemu.form.tinymce.script_url'),
            'width' => $this->container->getParameter('genemu.form.tinymce.width'),
            'height' => $this->container->getParameter('genemu.form.tinymce.height'),
            'config' => $this->container->getParameter('genemu.form.tinymce.config'),
            'required' => false
        );

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