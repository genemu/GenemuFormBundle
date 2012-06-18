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

use Doctrine\Common\Persistence\ObjectManager;

/**
 * AjaxEntityChoiceList
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class AjaxEntityChoiceList extends EntityChoiceList
{
    private $ajax;
    private $propertyPath;
    private $classMetadata;

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
    public function __construct(ObjectManager $em, $class, $property = null, $qb = null, $choices = null, $groupBy = null, $ajax = false)
    {
        $this->ajax = $ajax;
        $this->classMetadata = $em->getClassMetadata($class);

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
        $choices = $this->getRemainingViews();

        if (empty($choices)) {
            $choices = array();
        }

        $array = array();
        foreach ($choices as $choice) {
            $array[] = array(
                'value' => $choice->getValue(),
                'label' => $choice->getLabel()
            );
        }

        return $array;
    }

    /**
     * {@inheritdoc}
     */
    public function getRemainingViews()
    {
        if ($this->ajax) {
            return array();
        }

        return parent::getRemainingViews();
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
            foreach ($this->getChoicesForValues($ids) as $entity) {
                $id = current($this->classMetadata->getIdentifierValues($entity));

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
