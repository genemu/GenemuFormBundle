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
use Symfony\Component\Form\Exception\FormException;

/**
 * EntityIdToJsonTransform
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */

class EntityIdsToJsonTransformer implements DataTransformerInterface
{
    protected $choiceList;
    protected $routeName;

    /**
     * Construct
     *
     * @param EntityChoiceList $choiceList
     */
    public function __construct(EntityChoiceList $choiceList, $routeName = null)
    {
        $this->choiceList = $choiceList;
        $this->routeName = $routeName;
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
        if ($this->routeName) {
            foreach ($ids as $id) {
                $entity = $this->choiceList->getEntity($id);

                if (!method_exists($entity, '__toString')) {
                    throw new FormException('Entities passed to the choice field must have a "__toString()" method defined because use Ajax.');
                }

                $array[] = array(
                    'label' => $entity->__toString(),
                    'value' => $id
                );
            }
        } else {
            $choices = $this->choiceList->getChoices();
            foreach ($ids as $id) {
                $array[] = array(
                    'label' => $choices[$id],
                    'value' => $id
                );
            }
        }

        return json_encode($array);
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($values)
    {
        $array = array();
        foreach (json_decode($values, true) as $value) {
            $array[] = $value['value'];
        }

        return $array;
    }
}
