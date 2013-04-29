<?php

/*
 * This file is part of the GenemuFormBundle package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Form\Model\ChoiceList;

use Symfony\Bridge\Propel1\Form\ChoiceList\ModelChoiceList;
use Symfony\Component\PropertyAccess\PropertyPath;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * AjaxModelChoiceList
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class AjaxModelChoiceList extends ModelChoiceList
{
    private $ajax;
    private $propertyPath;

    /**
     * Constructs
     *
     * @param string         $class
     * @param string         $property
     * @param array|\Closure $choices
     * @param QueryObject    $qo
     * @param boolean        $ajax
     */
    public function __construct($class, $property = null, $choices = array(), $qo = null, $ajax = false)
    {
        $this->ajax = $ajax;

        if ($property) {
            $this->propertyPath = new PropertyPath($property);
        }

        parent::__construct($class, $property, $choices, $qo);
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
                $model = $this->getModel($id);

                if ($this->propertyPath) {
                    $label = PropertyAccess::getPropertyAccessor()->getValue($model, $this->propertyPath);
                } else {
                    $label = (string) $model;
                }

                $intersect[] = array(
                    'value' => $id,
                    'label' => $label
                );
            }
        } else {
            foreach ($this->getChoices() as $choice) {
                if (in_array($choice->value, $ids)) {
                    $intersect[] = $choice;
                }
            }
        }

        return $intersect;
    }
}
