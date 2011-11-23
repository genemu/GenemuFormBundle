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

use Symfony\Bundle\DoctrineMongoDBBundle\Form\ChoiceList\DocumentChoiceList;
use Doctrine\ODM\MongoDB\DocumentManager;

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class AutocompleteDocumentChoiceList extends DocumentChoiceList
{
    private $ajax;

    public function __construct(DocumentManager $dm, $class, $property = null, $queryBuilder = null, $choices = array(), $ajax = false)
    {
        $this->ajax = $ajax;

        parent::__construct($dm, $class, $property, $queryBuilder, $choices);
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
