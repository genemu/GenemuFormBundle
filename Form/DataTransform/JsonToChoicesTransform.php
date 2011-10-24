<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Form\DataTransform;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

/**
 * JQueryAutocompleterDataTransform
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */

class JsonToChoicesTransform implements DataTransformerInterface
{
    /**
     * {@inheritdoc}
     */
    public function transform($values)
    {
        if (null === $values) {
            return array();
        }

        if (is_null($values) || !$values) {
            return array();
        }

        if (!is_array($values)) {
            return UnexpectedTypeException($values, 'array');
        }

        $results = array();
        foreach ($values as $value => $label) {
            $results[] = array(
                'label' => $label,
                'value' => $value
            );
        }

        return json_encode($results);
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($values)
    {
        $results = array();

        $values = json_decode($values[0]);
        foreach ($values as $value) {
            $results[$value->value] = $value->label;
        }

        return $results;
    }
}
