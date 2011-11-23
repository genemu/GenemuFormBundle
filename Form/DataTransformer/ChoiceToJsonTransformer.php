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
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Extension\Core\ChoiceList\ArrayChoiceList;

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */

class ChoiceToJsonTransformer implements DataTransformerInterface
{
    protected $choiceList;
    protected $widget;
    protected $multiple;
    protected $ajax;

    public function __construct(ArrayChoiceList $choiceList, $widget = 'choice', $multiple = false, $ajax = false)
    {
        $this->choiceList = $choiceList;
        $this->widget = $widget;
        $this->multiple = $multiple;
        $this->ajax = $ajax;
    }

    /**
     * {@inheritdoc}
     */
    public function transform($choices)
    {
        if (null === $choices || !$choices) {
            return;
        }

        if (!is_array($choices) && !is_scalar($choices)) {
            throw new UnexpectedTypeException($choices, 'array or scalar');
        }

        $json = array();
        if ($this->multiple) {
            if ($this->ajax) {
                if ('entity' === $this->widget) {
                    foreach ($choices as $id) {
                        $entity = $this->choiceList->getEntity($id);

                        $json[] = array(
                            'label' => $entity->__toString(),
                            'value' => $id
                        );
                    }
                } elseif ('document' === $this->widget) {
                    foreach ($choices as $id) {
                        $document = $this->choiceList->getDocument($id);

                        $json[] = array(
                            'label' => $document->__toString(),
                            'value' => $id
                        );
                    }
                } else {
                    foreach ($choices as $value => $label) {
                        $json[] = array(
                            'label' => $label,
                            'value' => $value
                        );
                    }
                }
            } else {
                foreach ($this->choiceList->getChoices() as $choice) {
                    if (in_array($choice['value'], $choices)) {
                        $json[] = $choice;
                    }
                }
            }
        } else {
            if ($this->ajax) {
                if ('entity' === $this->widget) {
                    $entity = $this->choiceList->getEntity($choices);

                    $json = array(
                        'label' => $entity->__toString(),
                        'value' => $choices
                    );
                } elseif ('document' === $this->widget) {
                    $document = $this->choiceList->getDocument($choices);

                    $json = array(
                        'label' => $document->__toString(),
                        'value' => $choices
                    );
                } else {
                    $json = array(
                        'label' => $choices,
                        'value' => $choices
                    );
                }
            } else {
                foreach ($this->choiceList->getChoices() as $choice) {
                    if ($choices === $choice['value']) {
                        $json = $choice;
                        break;
                    }
                }
            }
        }

        return json_encode($json);
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($json)
    {
        $jsons = json_decode($json[0], true);

        $choices = array();
        if ($this->multiple) {
            foreach ($jsons as $json) {
                if ($this->ajax && !in_array($this->widget, array('entity', 'document'))) {
                    $choices[$json['value']] = $json['label'];
                } else {
                    $choices[] = $json['value'];
                }
            }
        } else {
            $choices = $jsons['value'];
        }

        return $choices;
    }
}
