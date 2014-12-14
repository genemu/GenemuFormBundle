<?php

/*
 * This file is part of the GenemuFormBundle package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Form\Doctrine\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Doctrine\Common\Persistence\ManagerRegistry;

use Genemu\Bundle\FormBundle\Form\Doctrine\ChoiceList\AjaxEntityChoiceList;

/**
 * AjaxEntityType
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class AjaxEntityType extends AbstractType
{
    private $registry;

    /**
     * Constructs
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $registry = $this->registry;

        $resolver->setDefaults(array(
            'em'            => null,
            'class'         => null,
            'property'      => null,
            'query_builder' => null,
            'choices'       => null,
            'group_by'      => null,
            'ajax'          => false,
            'choice_list'   => function (Options $options, $previousValue) use ($registry) {
                return new AjaxEntityChoiceList(
                    $options['em'],
                    $options['class'],
                    $options['property'],
                    $options['query_builder'],
                    $options['choices'],
                    $options['group_by'],
                    $options['ajax']
                );
            }
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'entity';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'genemu_ajaxentity';
    }
}
