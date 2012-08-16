<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Form\Core\EventListener;

use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Event\DataEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Genemu\Bundle\FormBundle\Geolocation\AddressGeolocation;

/**
 * GeoListener
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class GeolocationListener implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public function onBindNormData(DataEvent $event)
    {
        $data = $event->getData();

        if (true === empty($data)) {
            return;
        }

        $address = $data['address'];
        
        $latitude = null;
        if (true === isset($data['latitude'])) {
            $latitude = $data['latitude'];
        }

        $longitude = null;
        if (true === isset($data['longitude'])) {
            $longitude = $data['lengitude'];
        }

        $locality = null;
        if (true === isset($data['locality'])) {
            $locality = $data['locality'];
        }

        $country = null;
        if (true === isset($data['country'])) {
            $contry = $data['country'];
        }

        $event->setData(new AddressGeolocation($address, $latitude, $locality, $country));
    }

    /**
     * {@inheritdoc}
     */
    static public function getSubscribedEvents()
    {
        return array(FormEvents::BIND_NORM_DATA => 'onBindNormData');
    }
}
