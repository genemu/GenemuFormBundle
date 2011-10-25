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
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

/**
 * JsonToEntityTransform
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */

class JsonToEntityTransform implements DataTransformerInterface
{
    protected $encoder;
    protected $choiceList;

    /**
     * Construct
     *
     * @param EntityChoiceList $choiceList
     */
    public function __construct(EntityChoiceList $choiceList)
    {
        $this->choiceList = $choiceList;
        $this->encoder = new JsonEncoder();
    }

    /**
     * {@inheritdoc}
     */
    public function transform($ids)
    {
        if (null === $ids || !$ids) {
            return array();
        }

        if (!is_array($ids)) {
            throw new UnexpectedTypeException($ids, 'array');
        }

        $array = array();
        foreach ($ids as $id) {
            $entity = $this->choiceList->getEntity($id);

            $array[] = array(
                'label' => $entity->__toString(),
                'value' => $id
            );
        }

        return $this->encoder->encode($array, 'json');
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($values)
    {
        $array = array();

        $values = $this->encoder->decode($values[0], 'json');
        foreach ($values as $value) {
            $array[] = $value['value'];
        }

        return $array;
    }
}
