<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Form\Doctrine\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

use Symfony\Bridge\Doctrine\RegistryInterface;

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
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions(array $options)
    {
        $defaultOptions = array(
            'em'            => null,
            'class'         => null,
            'property'      => null,
            'query_builder' => null,
            'choices'       => null,
            'ajax'          => false
        );

        $options = array_replace($defaultOptions, $options);

        $options['choice_list'] = new AjaxEntityChoiceList(
            $this->registry->getEntityManager($options['em']),
            $options['class'],
            $options['property'],
            $options['query_builder'],
            $options['choices'],
            $options['ajax']
        );

        return $options;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(array $options)
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
