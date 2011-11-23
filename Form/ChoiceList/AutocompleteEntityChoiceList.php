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

use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityChoiceList;

use Doctrine\ORM\EntityManager;

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class AutocompleteEntityChoiceList extends EntityChoiceList
{
    private $ajax;

    public function __construct(EntityManager $em, $class, $property = null, $queryBuilder = null, $choices = array(), $groupBy = null, $ajax = false)
    {
        $this->ajax = $ajax;

        parent::__construct($em, $class, $property, $queryBuilder, $choices, $groupBy);
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
}
