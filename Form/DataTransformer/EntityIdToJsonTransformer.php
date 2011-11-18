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
 * EntityIdToJsonTransform
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */

class EntityIdToJsonTransformer implements DataTransformerInterface
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
    public function transform($ids)
    {
        if (null === $ids || !$ids) {
            return null;
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

        return json_encode($array);
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($values)
    {
        $array = array();
        foreach (json_decode($values[0], true) as $value) {
            $array[] = $value['value'];
        }

        return $array;
    }
}
