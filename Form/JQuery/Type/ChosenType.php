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

/**
 * ChosenType to JQueryLib
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class ChosenType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->setAttribute('allow_single_deselect', $options['allow_single_deselect']);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form)
    {
        $view->set('allow_single_deselect', $form->getAttribute('allow_single_deselect'));
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions(array $options)
    {
        $defaultOptions = array(
            'widget' => 'choice',
            'allow_single_deselect' => true,
        );

        return array_replace($defaultOptions, $options);
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
        return $options['widget'];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'genemu_jquerychosen';
    }
}
