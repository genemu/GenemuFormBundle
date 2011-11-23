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
use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityChoiceList;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

/**
 * FieldToJsonTransform
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */

class ArrayToJsonTransformer implements DataTransformerInterface
{
    /**
     * {@inheritdoc}
     */
    public function transform($array)
    {
        if (null === $array || !$array) {
            return null;
        }

        if (!is_array($array)) {
            throw new UnexpectedTypeException($array, 'array');
        }

        $return = array();
        foreach ($array as $value => $label) {
            $return[] = array(
                'label' => $label,
                'value' => $value
            );
        }

        return json_encode($return);
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
