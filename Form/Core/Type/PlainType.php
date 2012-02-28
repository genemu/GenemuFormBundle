<?php

namespace Genemu\Bundle\FormBundle\Form\Core\Type;

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

/**
 * A Form type that just renders the field as a p tag. This is useful for forms where certain field
 * need to be shown but not editable.
 *
 * @author Adam Kuśmierz <adam@kusmierz.be>
 */
class PlainType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions(array $options)
    {
        $defaultOptions = array(
            'widget'  => 'field',
            'configs' => array(),
            'read_only' => true,
            'attr' => array(
                'class' => $this->getName()
            )
            //'property_path' => false,
        );

        return array_replace_recursive($defaultOptions, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(array $options)
    {
        return 'field';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'genemu_plain';
    }
}
