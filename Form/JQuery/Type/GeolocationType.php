<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Form\JQuery\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormViewInterface;
use Symfony\Component\Form\FormInterface;

use Genemu\Bundle\FormBundle\Form\Core\EventListener\GeolocationListener;

/**
 * GeolocationType to JQueryLib
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class GeolocationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('address', 'field');

        foreach (array('latitude', 'longitude', 'locality', 'country') as $field) {
            $option = $options[$field];

            if (isset($option['enabled']) && !empty($option['enabled'])) {
                $type = 'field';
                if (isset($option['hidden']) && !empty($option['hidden'])) {
                    $type = 'hidden';
                }

                $builder->add($field, $type);
            }
        }

        $builder
            ->addEventSubscriber(new GeolocationListener());
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormViewInterface $view, FormInterface $form, array $options)
    {
        $view
            ->addVars(array(
                'configs' => array('elements' => array()),
                'map' => $options['map']
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions()
    {
        return array(
            'map' => false,
            'latitude' => array(
                'enabled' => false,
                'hidden' => false,
            ),
            'longitude' => array(
                'enabled' => false,
                'hidden' => false,
            ),
            'locality' => array(
                'enabled' => false,
                'hidden' => false,
            ),
            'country' => array(
                'enabled' => false,
                'hidden' => false,
            ),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'form';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'genemu_jquerygeolocation';
    }
}
