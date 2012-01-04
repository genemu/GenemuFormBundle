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
 * RatingType
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class RatingType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->setAttribute('configs', $options['configs']);
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
            'configs' => array(),
        );

        $options = array_replace($defaultOptions, $options);

        if (false === isset($options['expanded']) || (true === isset($options['examded']) && false === empty($options['expanded']))) {
            $options['configs']['inputType'] = 'select';
        }

        return $options;
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
        return 'genemu_jqueryrating';
    }
}
