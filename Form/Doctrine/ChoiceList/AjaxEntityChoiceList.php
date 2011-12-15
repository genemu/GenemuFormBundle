<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Form\Doctrine\ChoiceList;

use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityChoiceList;
use Symfony\Component\Form\Util\PropertyPath;

use Doctrine\ORM\EntityManager;

/**
 * AjaxEntityChoiceList
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class AjaxEntityChoiceList extends EntityChoiceList
{
    private $ajax;
    private $propertyPath;

    /**
     * Constructs
     *
     * @param EntityManager  $em
     * @param string         $class
     * @param string         $property
     * @param QueryBuilder   $qb
     * @param array|\Closure $choices
     * @param string         $groupBy
     * @param boolean        $ajax
     */
    public function __construct(EntityManager $em, $class, $property = null, $qb = null, $choices = array(), $groupBy = null, $ajax = false)
    {
        $this->ajax = $ajax;

        if ($property) {
            $this->propertyPath = new PropertyPath($property);
        }

        parent::__construct($em, $class, $property, $qb, $choices, $groupBy);
    }

    /**
     * {@inheritdoc}
     */
    protected function load()
    {
        if (!$this->ajax) {
            parent::load();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getChoices()
    {
        $choices = parent::getChoices();

        $array = array();
        foreach ($choices as $value => $label) {
            $array[] = array(
                'value' => $value,
                'label' => $label
            );
        }

        return $array;
    }

    /**
     * Get intersaction $choices to $ids
     *
     * @param array $ids
     *
     * @return array $intersect
     */
    public function getIntersect(array $ids)
    {
        $intersect = array();

        if ($this->ajax) {
            foreach ($ids as $id) {
                $entity = $this->getEntity($id);

                if ($this->propertyPath) {
                    $label = $this->propertyPath->getValue($entity);
                } else {
                    $label = (string) $entity;
                }

                $intersect[] = array(
                    'value' => $id,
                    'label' => $label
                );
            }
        } else {
            foreach ($this->getChoices() as $choice) {
                if (in_array($choice['value'], $ids)) {
                    $intersect[] = $choice;
                }
            }
        }

        return $intersect;
    }
}
