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

use Symfony\Component\Form\Extension\Core\ChoiceList\ArrayChoiceList;

/**
 * AjaxArrayChoiceList
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class AjaxArrayChoiceList extends ArrayChoiceList
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
     * Get intersaction $choices to $values
     *
     * @param array $values
     *
     * @return array $intersect
     */
    public function getIntersect(array $values)
    {
        $intersect = array();

        if (true === $this->ajax) {
            foreach ($values as $value => $label) {
                $intersect[] = array(
                    'value' => $value,
                    'label' => $label
                );
            }
        } else {
            foreach ($this->getChoices() as $choice) {
                if (true === in_array($choice['value'], $values, true)) {
                    $intersect[] = $choice;
                }
            }
        }

        return $intersect;
    }
}
