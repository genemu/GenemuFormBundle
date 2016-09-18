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
class AddressGeolocation implements \Serializable, \ArrayAccess
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

    public function setAddress($a = null)
    {
        $this->address = $a;
    }

    public function getLatitude()
    {
        return $this->latitude;
    }

    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    public function getLongitude()
    {
        return $this->longitude;
    }

    public function setLongitude($long)
    {
        $this->longitude = $long;
    }

    public function getLocality()
    {
        return $this->locality;
    }

    public function setLocality($locality)
    {
        $this->locality = $locality;
    }

    public function setCountry($country)
    {
        $this->country = $country;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function serialize()
    {
        return serialize(array(
                'address' => $this->address,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'locality' => $this->locality,
                'country' => $this->country,
            ));
    }

    public function unserialize($serialized)
    {
        $data = unserialize($serialized);

        $this->address = $data['address']  ?: null;
        $this->latitude = $data['latitude'] ?: null;
        $this->longitude = $data['longitude'] ?: null;
        $this->locality = $data['locality'] ?: null;
        $this->country = $data['country'] ?: null;
    }

    public function offsetExists($offset)
    {
        return isset($this->$offset);
    }

    public function offsetSet($offset, $value)
    {
        $this->$offset = $value;
    }

    public function offsetGet($offset)
    {
        return $this->$offset;
    }

    public function offsetUnset($offset)
    {
        $this->$offset = null;
    }
}
