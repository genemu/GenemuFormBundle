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

/**
 * JsonToChoicesTransform
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */

class ChoiceToJsonTransformer implements DataTransformerInterface
{
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
        foreach ($choices as $value => $label) {
            $array[] = array(
                'label' => $label,
                'value' => $value
            );
        }

        return json_encode($array);
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($values)
    {
        return json_decode($values[0], true);
    }
}
