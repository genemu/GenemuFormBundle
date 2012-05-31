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
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * ChosenType to JQueryLib
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 * @author Bilal Amarni <bilal.amarni@gmail.com>
 */
class ChosenType extends AbstractType
{
    private $widget;

    public function __construct($widget)
    {
        $this->widget = $widget;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setAttribute('allow_single_deselect', $options['allow_single_deselect']);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormViewInterface $view, FormInterface $form, array $options)
    {
        $view->setVar('allow_single_deselect', $form->getAttribute('allow_single_deselect'));
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'allow_single_deselect' => true,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return $this->widget;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'genemu_jquerychosen';
    }
}
