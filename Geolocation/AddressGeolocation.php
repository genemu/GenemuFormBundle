<?php

/*
 * This file is part of the GenemuFormBundle package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Geolocation;

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class AddressGeolocation implements \Serializable
{
    private $address;
    private $latitude;
    private $longitude;
    private $locality;
    private $country;

    public function __construct($address, $latitude = null, $longitude = null, $locality = null, $country = null)
    {
        $this->address = $address;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->locality = $locality;
        $this->country = $country;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function getLatitude()
    {
        return $this->latitude;
    }

    public function getLongitude()
    {
        return $this->longitude;
    }

    public function getLocality()
    {
        return $this->locality;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function serialize()
    {
        return serialize(array(
                'address'   => $this->address,
                'latitude'  => $this->latitude,
                'longitude' => $this->longitude,
                'locality'  => $this->locality,
                'country'   => $this->country
            ));
    }

    public function unserialize($serialized)
    {
        $data = unserialize($serialized);

        $this->address  = $data['address']  ?: null;
        $this->latitude = $data['latitude'] ?: null;
        $this->longitude = $data['longitude'] ?: null;
        $this->locality = $data['locality'] ?: null;
        $this->country = $data['country'] ?: null;
    }
}
