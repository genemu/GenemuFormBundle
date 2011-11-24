<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Form\Type\Entity;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Common\Persistence\ManagerRegistry;

use Genemu\Bundle\FormBundle\Form\ChoiceList\AjaxEntityChoiceList;

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class AjaxEntityType extends AbstractType
{
    protected $registry;
    protected $method;

    public function __construct($registry)
    {
        if (!$registry instanceof RegistryInterface && !$registry instanceof ManagerRegistry) {
            throw new \InvalidArgumentException(
                '__construct accept a RegistryInterface or ManagerRegistry.'
            );
        }

        $this->registry = $registry;
        $this->method = $registry instanceof ManagerRegistry ? 'getManager' : 'getEntityManager';
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
            'choices'       => array(),
            'group_by'      => null,
            'ajax'          => false
        );

        $options = array_replace($defaultOptions, $options);

        $method = $this->method;
        $options['choice_list'] = new AjaxEntityChoiceList(
            $this->registry->$method($options['em']),
            $options['class'],
            $options['property'],
            $options['query_builder'],
            $options['choices'],
            $options['group_by'],
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
