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
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormView;
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
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('address', 'field');

        foreach (array('latitude', 'longitude', 'locality', 'country') as $field) {
            $option = $options[$field];

            if (true === isset($option['enabled']) && false === empty($option['enabled'])) {
                $type = 'field';
                if (true === isset($option['hidden']) && false === empty($option['hidden'])) {
                    $type = 'hidden';
                }

                $builder->add($field, $type);
            }
        }

        $builder
            ->addEventSubscriber(new GeolocationListener())
            ->setAttribute('map', $options['map']);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form)
    {
        $view
            ->set('configs', array('elements' => array()))
            ->set('map', $form->getAttribute('map'));
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions(array $options)
    {
        $defaultOptions = array(
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

        return array_replace($defaultOptions, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(array $options)
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
