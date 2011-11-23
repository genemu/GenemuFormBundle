<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Form\ChoiceList;

use Symfony\Component\Form\Extension\Core\ChoiceList\ArrayChoiceList;

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class AutocompleteArrayChoiceList extends ArrayChoiceList
{
    private $ajax;

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
}
