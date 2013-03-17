<?php

/*
 * This file is part of the GenemuFormBundle package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Form\Model\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Genemu\Bundle\FormBundle\Form\Model\ChoiceList\AjaxModelChoiceList;

/**
 * AjaxModelType
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class AjaxModelType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'template'          => 'choice',
            'multiple'          => false,
            'expanded'          => false,
            'class'             => null,
            'property'          => null,
            'query'             => null,
            'choices'           => array(),
            'preferred_choices' => array(),
            'ajax'              => false,
            'choice_list'       => function (Options $options, $previousValue) {
                if (null === $previousValue) {
                    if (!isset($options['choice_list'])) {
                        return new AjaxModelChoiceList(
                            $options['class'],
                            $options['property'],
                            $options['choices'],
                            $options['query'],
                            $options['ajax']
                        );
                    }
                }

                return null;
            }
        ));

        return $options;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'model';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'genemu_ajaxmodel';
    }
}
