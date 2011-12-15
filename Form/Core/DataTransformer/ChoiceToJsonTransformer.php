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
use Symfony\Component\Form\Extension\Core\ChoiceList\ArrayChoiceList;

/**
 * ChoiceToJsonTransformer
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class ChoiceToJsonTransformer implements DataTransformerInterface
{
    protected $choiceList;
    protected $widget;
    protected $multiple;
    protected $ajax;

    /**
     * Constructs
     *
     * @param ArrayChoiceList $choiceList
     * @param string          $widget
     * @param boolean         $multiple
     * @param boolean         $ajax
     */
    public function __construct(ArrayChoiceList $choiceList, $widget = 'choice', $multiple = false, $ajax = false)
    {
        $this->choiceList = $choiceList;
        $this->multiple = $multiple;
        $this->widget = $widget;
        $this->ajax = $ajax;
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

        $json = $this->choiceList->getIntersect($choices);

        if (!$this->multiple) {
            $json = current($json);
        }

        return json_encode($json);
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($json)
    {
        $values = json_decode(is_array($json) ? current($json) : $json, true);

        if ($this->multiple) {
            $choices = array();

            foreach ($values as $value) {
                if (
                    $this->ajax &&
                    !in_array($this->widget, array('entity', 'document', 'model'), true)
                ) {
                    $choices[$value['value']] = $value['label'];
                } else {
                    $choices[] = $value['value'];
                }
            }
        } else {
            $choices = $values['value'];
        }

        return $choices;
    }
}
