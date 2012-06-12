<?php

namespace Genemu\Bundle\FormBundle\Form\Core\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormViewInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        return array(
            'widget'  => 'field',
            'read_only' => true,
            'attr' => array(
                'class' => $this->getName()
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormViewInterface $view, FormInterface $form, array $options)
    {
        $value = $form->getViewData();

        // set string representation
        if (true === $value) {
            $value = 'true';
        } else if (false === $value) {
            $value = 'false';
        } else if (null === $value) {
            $value = 'null';
        } else if (is_array($value)) {
            $value = implode(', ', $value);
        } else if ($value instanceof \DateTime) {
            $value = $value->format('Y-m-d H:i:s');
        } else if (is_object($value)) {
            if (method_exists($value, '__toString')) {
                $value = $value->__toString();
            } else {
                $value = get_class($value);
            }
        }

        $view->setVar('value', (string) $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
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
