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

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\Exception\FormException;

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class SliderType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        $configs = array(
            'min'         => $options['min'],
            'max'         => $options['max'],
            'step'        => $options['step'],
            'orientation' => $options['orientation']
        );

        $builder->setAttribute('configs', $configs);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form)
    {
        $view->set('configs', $form->getAttribute('configs'));
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions(array $options)
    {
        $defaultOptions = array(
            'min'         => 0,
            'max'         => 100,
            'step'        => 1,
            'orientation' => 'horizontal'
        );

        $options = array_replace($defaultOptions, $options);

        if (!in_array($options['orientation'], array('horizontal', 'vertical'))) {
            throw new FormException('The option "orientation" is not vaild.');
        }

        return $options;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(array $options)
    {
        return 'integer';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'genemu_jqueryslider';
    }
}
