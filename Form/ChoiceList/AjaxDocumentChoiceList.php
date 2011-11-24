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
use Symfony\Component\Form\Util\PropertyPath;
use Doctrine\ODM\MongoDB\DocumentManager;

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class AjaxDocumentChoiceList extends DocumentChoiceList
{
    private $ajax;
    private $propertyPath;

    public function __construct(DocumentManager $dm, $class, $property = null, $queryBuilder = null, $choices = array(), $ajax = false)
    {
        $this->ajax = $ajax;

        if ($property) {
            $this->propertyPath = new PropertyPath($property);
        }

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
                $document = $this->getDocument($id);

                if ($this->propertyPath) {
                    $label = $this->propertyPath->getValue($document);
                } else {
                    $label = (string) $document;
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
