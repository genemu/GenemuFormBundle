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
use Symfony\Component\OptionsResolver\Options;

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
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setAttribute('configs', $options['configs']);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormViewInterface $view, FormInterface $form, array $options)
    {

        $view->setVar('configs', $form->getAttribute('configs'));
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions()
    {
        $options = array(
            'configs' => function (Options $options, $previousValue) {
                $configs = array();

                if (null === $previousValue) {
                    if (!isset($options['expanded']) || (isset($options['examded']) && !$options['expanded'])) {
                        $configs['inputType'] = 'select';
                    }
                }

                return $configs;
            }
        );

        return $options;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
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
