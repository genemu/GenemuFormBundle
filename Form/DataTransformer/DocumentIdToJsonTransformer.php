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
use Symfony\Bundle\DoctrineMongoDBBundle\Form\ChoiceList\DocumentChoiceList;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Exception\FormException;

/**
 * DocumentIdToJsonTransform
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */

class DocumentIdToJsonTransformer implements DataTransformerInterface
{
    protected $choiceList;
    protected $rooteName;

    /**
     * Construct
     *
     * @param DocumentChoiceList $choiceList
     */
    public function __construct(DocumentChoiceList $choiceList, $routeName = null)
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

        if (!is_integer($id) && !is_string($id)) {
            throw new UnexpectedTypeException($id, 'integer or string');
        }

        if ($this->routeName) {
            $entity = $this->choiceList->getDocument($id);

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
        $value = is_array($value) ? current($value) : $value;
        $value = json_decode($value, true);

        return $value['value'];
    }
}
