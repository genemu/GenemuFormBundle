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
use Symfony\Component\Serializer\Encoder\JsonEncoder;

/**
 * JsonToChoicesTransform
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */

class JsonToChoicesTransform implements DataTransformerInterface
{
    protected $encoder;

    /**
     * Construct
     */
    public function __construct()
    {
        $this->encoder = new JsonEncoder();
    }

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
            throw new UnexpectedTypeException($values, 'array');
        }

        $array = array();
        foreach ($values as $value => $label) {
            $array[] = array(
                'label' => $label,
                'value' => $value
            );
        }

        return $this->encoder->encode($array, 'json');
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($values)
    {
        return $this->decode($values[0], 'json');
    }
}
