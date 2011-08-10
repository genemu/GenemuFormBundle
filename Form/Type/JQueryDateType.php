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

/**
 * JQueryDateType
 *
 * @author Olivier Chauvel <olivier@gmail.com>
 */
class JQueryDateType extends AbstractType
{
    protected $options;

    /**
     * Construct.
     *
     * @param string $image
     * @param string $config
     */
    public function __construct($image, $config)
    {
        $this->options = array(
            'image' => $image,
            'config' => $config
        );
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->setAttribute('min_year', min($options['years']))
            ->setAttribute('max_year', max($options['years']))
            ->setAttribute('image', $options['image'])
            ->setAttribute('config', ($options['config'])?$options['config']:'{}');
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form)
    {
        if($form->getAttribute('widget') == 'single_text') {
            $pattern = $form->getAttribute('formatter')->getPattern();
        } else {
            $pattern = 'yy-mm-dd';
        }

        $view
            ->set('min_year', $form->getAttribute('min_year'))
            ->set('max_year', $form->getAttribute('max_year'))
            ->set('image', $form->getAttribute('image'))
            ->set('config', $form->getAttribute('config'))
            ->set('culture', \Locale::getDefault())
            ->set('javascript_format', $pattern);
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
        return 'date';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'genemu_jquerydate';
    }
}