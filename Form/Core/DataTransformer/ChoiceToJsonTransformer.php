<?php

/*
 * This file is part of the Symfony package.
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

        if (!$this->multiple) {
            if ($this->ajax && !in_array($this->widget, array('entity', 'document', 'model'))) {
                $this->choiceList->addAjaxChoice($choices);
            }

            return $choices['value'];
        }

        $choices = array_unique($choices, SORT_REGULAR);

        $values = array();

        foreach ($choices as $choice) {
            if ($this->ajax && !in_array($this->widget, array('entity', 'document', 'model'))) {
                $this->choiceList->addAjaxChoice($choice);
            }

            $values[] = $choice['value'];
        }

        return $values;
    }
}
