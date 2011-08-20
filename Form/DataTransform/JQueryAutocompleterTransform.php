<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olchauvel@gmail.com>
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
 * @author Olivier Chauvel <olchauvel@gmail.com>
 */

class JQueryAutocompleterTransform implements DataTransformerInterface
{
    /**
     * {@inheritdoc}
     */
    public function transform($values)
    {
        if (is_null($values)) {
            return '';
        }

        if (!is_array($values)) {
            return UnexpectedTypeException($values, 'array');
        }

        return implode(', ', $values);
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($values)
    {
        $results = array();

        foreach (explode(',', $values[0]) as $value) {
            if (trim($value) !== '') {
                $results[] = $value;
            }
        }

        return $results;
    }
}
