<?php

/*
 * This file is part of the GenemuFormBundle package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Form\Core\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceListInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * ChoiceToJsonTransformer
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class ChoiceToJsonTransformer implements DataTransformerInterface
{
    protected $choiceList;
    protected $ajax;
    protected $widget;
    protected $multiple;

    /**
     * Constructs
     *
     * @param ArrayChoiceList $choiceList
     * @param boolean         $ajax
     */
    public function __construct(ChoiceListInterface $choiceList, $ajax = false, $widget = 'choice', $multiple = false)
    {
        $this->choiceList = $choiceList;
        $this->ajax = $ajax;
        $this->multiple = $multiple;
        $this->widget = $widget;
    }

    /**
     * {@inheritdoc}
     */
    public function transform($choices)
    {
        if (empty($choices)) {
            return;
        }

        if (is_scalar($choices)) {
            $choices = array($choices);
        }

        if (!is_array($choices)) {
            throw new UnexpectedTypeException($choices, 'array');
        }

        $choices = $this->choiceList->getIntersect($choices);

        if (!$this->multiple) {
            $choices = current($choices);
        }

        return json_encode($choices);
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($json)
    {
        $choices = json_decode(is_array($json) ? current($json) : $json, true);
        
        // Single choice list
        if (!$this->multiple) {

            if (empty($choices)) {
                return '';
            }

            if (!$this->isSimpleValue($choices)) {
                throw new TransformationFailedException('The format of the json array is bad');
            }

            $this->addAjaxChoices($choices);

            return $choices['value'];

        } else {

            if (empty($choices)) {
                return array();
            }

            if (!$this->isArrayValue($choices)) {
                throw new TransformationFailedException('The format of the json array is bad');
            }

            $choices = array_unique($choices, SORT_REGULAR);

            $values = array();

            foreach ($choices as $choice) {
                $this->addAjaxChoices($choice);

                $values[] = $choice['value'];
            }

            return $values;
        }
    }

    private function addAjaxChoices(&$choices)
    {
        if ($this->ajax && !in_array($this->widget, array('entity', 'document', 'model'))) {
            $this->choiceList->addAjaxChoice($choices);
        }
    }

    /**
     * Checks if the argument has 'value' and 'label' keys
     */
    private function isSimpleValue($array)
    {
        return is_array($array)
            && array_key_exists('value', $array)
            && array_key_exists('label', $array);
    }

    /**
     * Checks if the argument is an array of simple values
     */
    private function isArrayValue($array)
    {
        foreach ($array as $item) {
            if (!$this->isSimpleValue($item)) {
                return false;
            }
        }
        return true;
    }
}
