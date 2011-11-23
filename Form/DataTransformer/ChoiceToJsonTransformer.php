<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Extension\Core\ChoiceList\ArrayChoiceList;

/**
 * JsonToChoicesTransform
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */

class ChoiceToJsonTransformer implements DataTransformerInterface
{
    protected $choiceList;

    /**
     * Construct
     *
     * @param ArrayChoiceList $choiceList
     */
    public function __construct(ArrayChoiceList $choiceList)
    {
        $this->choiceList = $choiceList;
    }

    /**
     * {@inheritdoc}
     */
    public function transform($choices)
    {
        if (null === $choices || !$choices) {
            return null;
        }

        if (!is_array($choices)) {
            throw new UnexpectedTypeException($choices, 'array');
        }

        $array = array();
        foreach ($choices as $value) {
            foreach ($this->choiceList->getChoices() as $list) {
                if ($value === $list['value']) {
                    $array[] = array(
                        'label' => $list['label'],
                        'value' => $value
                    );
                }
            }
        }

        return json_encode($array);
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($values)
    {
        $values = is_array($values) ? current($values) : $values;
        $values = json_decode($values, true);

        return $values;
    }
}
