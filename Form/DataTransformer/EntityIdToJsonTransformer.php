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

class EntityIdToJsonTransformer implements DataTransformerInterface
{
    protected $choiceList;
    protected $rooteName;

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
    public function transform($id)
    {
        if (null === $id || !$id) {
            return null;
        }

        if (!is_integer($id)) {
            throw new UnexpectedTypeException($id, 'integer');
        }

        if ($this->routeName) {
            $entity = $this->choiceList->getEntity($id);

            if (!method_exists($entity, '__toString')) {
                    throw new FormException('Entities passed to the choice field must have a "__toString()" method defined because use Ajax.');
            }

            return json_encode(array(
                'label' => $entity->__toString(),
                'value' => $id
            ));
        } else {
            $choices = $this->choiceList->getChoices();

            return json_encode(array(
                'label' => $choices[$id],
                'value' => $id
            ));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value)
    {
        $value = json_decode($value, true);

        return $value['value'];
    }
}
