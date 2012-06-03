<?php

/*
 * This file is part of the Symfony package.
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
                'value' => $choice->getValue(),
                'label' => $choice->getLabel()
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
            foreach ($values as $value => $label) {
                $intersect[] = array(
                    'value' => $value,
                    'label' => $label,
                );
            }
        } else {
            foreach ($this->getRemainingViews() as $choice) {
                if (in_array($choice->getValue(), $values, true)) {
                    $intersect[] = array(
                        'value' => $choice->getValue(),
                        'label' => $choice->getLabel(),
                    );
                }
            }
        }

        return $intersect;
    }

    /**
     * Get intersaction $choices to $values
     * including all freeValues
     * @param array $values
     *
     * @return array $intersect
     */

    public function getIntersectFreeValues(array $values)
    {
        $intersect = array();

        foreach ($values as $value) {
            $intersect[] = array(
                'value' => $value,
                'label' => $value
            );
        }

        return $intersect;
    }
}
