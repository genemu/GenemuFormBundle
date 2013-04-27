<?php

/*
 * This file is part of the GenemuFormBundle package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Form\Core\EventListener;

use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
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
    public function onBind(FormEvent $event)
    {
        $data = $event->getData();

        if (empty($data)) {
            return;
        }

        $address = $data['address'];
        $latitude = isset($data['latitude']) ? $data['latitude'] : null;
        $longitude = isset($data['longitude']) ? $data['longitude'] : null;
        $locality = isset($data['locality']) ? $data['locality'] : null;
        $country = isset($data['country']) ? $data['country'] : null;

        $geo = new AddressGeolocation($address, $latitude, $longitude, $locality, $country);

        $event->setData($geo);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(FormEvents::BIND => 'onBind');
    }
}
