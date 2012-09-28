<?php

/*
 * This file is part of the GenemuFormBundle package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Form\Core\ChoiceList;

use Symfony\Component\Form\Extension\Core\ChoiceList\SimpleChoiceList;

/**
 * AjaxArrayChoiceList
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class AjaxSimpleChoiceList extends SimpleChoiceList
{
    private $ajax;

    private $ajaxChoices = array();

    /**
     * Constructs
     *
     * @param array|\Closure $choices
     * @param boolean        $ajax
     */
    public function __construct($choices, $ajax = false)
    {
        $this->ajax = $ajax;

        parent::__construct($choices);
    }

    /**
     * {@inheritdoc}
     */
    public function getChoices()
    {
        $choices = parent::getRemainingViews();

        $array = array();
        foreach ($choices as $choice) {
            $array[] = array(
                'value' => $choice->value,
                'label' => $choice->label
            );
        }

        return $array;
    }

    /**
     * Get intersaction $choices to $values
     *
     * @param array $values
     *
     * @return array $intersect
     */
    public function getIntersect(array $values)
    {
        $intersect = array();

        if ($this->ajax) {
            foreach ($values as $value) {
                $key = array_search($value, $this->ajaxChoices);
                if ($key) {
                    $intersect[] = array(
                        'value' => $key,
                        'label' => $value,
                    );
                }
            }
        } else {
            foreach ($this->getChoices() as $choice) {
                if (in_array($choice['value'], $values, true)) {
                    $intersect[] = array(
                        'value' => $choice['value'],
                        'label' => $choice['label'],
                    );
                }
            }
        }

        return $intersect;
    }

    public function getValuesForChoices(array $values)
    {
        if (!$this->ajax) {
            return parent::getValuesForChoices($values);
        }

        $intersect = array();

        foreach ($values as $value) {
            if (isset($this->ajaxChoices[$value])) {
                $intersect[] = $this->ajaxChoices[$value];
            } else {
                $intersect[] = $value;
            }
        }

        return $intersect;
    }

    public function getChoicesForValues(array $values)
    {
        if (!$this->ajax) {
            return parent::getChoicesForValues($values);
        }

        return $values;
    }

    public function addAjaxChoice(array $choice)
    {
        $this->ajaxChoices[$choice['value']] = $choice['label'];
    }
}
