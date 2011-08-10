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
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilder;

/**
 * DoubleListType
 *
 * @author Olivier Chauvel <olivier@gmail.com>
 */
class DoubleListType extends AbstractType
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
        $unassociatedOptions = array(
            'multiple' => $options['multiple'],
            'required' => $options['required']
        );

        $unassociated = $builder->create('unassociated', 'choice', $unassociatedOptions)->getForm()->createView();

        $unassociated
            ->set('attr', array_merge($unassociated->get('attr'), array('class' => $options['class_select'])))
            ->set('label', $options['label_unassociated']);

        $builder
            ->setAttribute('unassociated', $unassociated)
            ->setAttribute('label_associated', $options['label_associated'])
            ->setAttribute('associated_first', $options['associated_first'])
            ->setAttribute('class', $options['class'])
            ->setAttribute('class_select', $options['class_select']);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form)
    {
        $values = $form->getClientData();
        $choices = $form->getAttribute('choice_list')->getChoices();

        $associatedValues = array();
        $unassociatedValues = array();
        foreach($choices as $key => $option) {
            if(in_array(strval($key), $values)) {
                $associatedValues[$key] = $option;
            } else {
                $unassociatedValues[$key] = $option;
            }
        }

        $unassociated = $form->getAttribute('unassociated')
            ->set('choices', $unassociatedValues);

        $view
            ->set('choices', $associatedValues)
            ->set('attr', array_merge($view->get('attr'), array('class' => $form->getAttribute('class_select').'-selected')));

        if($form->getAttribute('label_associated')) {
            $float = 'left';
            $next = $view->get('id');
            $previous = $unassociated->get('id');
        } else {
            $float = 'right';
            $next = $unassociated->get('id');
            $previous = $view->get('id');
        }

        $view
            ->set('label_associated', $form->getAttribute('label_associated'))
            ->set('unassociated', $unassociated)
            ->set('float', $float)
            ->set('next', $next)
            ->set('previous', $previous)
            ->set('associated_first', $form->getAttribute('associated_first'))
            ->set('class', $form->getAttribute('class'))
            ->set('class_select', $form->getAttribute('class_select'));
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions(array $options)
    {
        $defaultOptions = array(
            'class' => $this->container->getParameter('genemu.form.doublelist.class'),
            'class_select' => $this->container->getParameter('genemu.form.doublelist.class_select'),
            'label_associated' => $this->container->getParameter('genemu.form.doublelist.label_associated'),
            'label_unassociated' => $this->container->getParameter('genemu.form.doublelist.label_unassociated'),
            'associated_first' => $this->container->getParameter('genemu.form.doublelist.associated_first'),
            'multiple' => true,
            'required' => false
        );

        return array_replace($defaultOptions, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(array $options)
    {
        return 'choice';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'genemu_doublelist';
    }
}