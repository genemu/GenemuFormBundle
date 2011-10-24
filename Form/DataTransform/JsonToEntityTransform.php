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
use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityChoiceList;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

/**
 * JsonToEntityTransform
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */

class JsonToEntityTransform implements DataTransformerInterface
{
    protected $choiceList;

    /**
     * Construct
     *
     * @param EntityChoiceList $choiceList
     */
    public function __construct(EntityChoiceList $choiceList)
    {
        $this->choiceList = $choiceList;
    }

    /**
     * {@inheritdoc}
     */
    public function transform($identifiers)
    {
        if (null === $identifiers || !$identifiers) {
            return array();
        }

        if (!is_array($identifiers)) {
            throw new UnexpectedTypeException($values, 'array');
        }

        $array = array();
        foreach ($identifiers as $identifier) {
            $entity = $this->choiceList->getEntity($identifier);

            $array[] = array(
                'label' => $entity->__toString(),
                'value' => $identifier
            );
        }

        return json_encode($array);
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($values)
    {
        $results = array();

        $values = json_decode($values[0]);
        foreach ($values as $value) {
            $results[] = $value->value;
        }

        return $results;
    }
}
