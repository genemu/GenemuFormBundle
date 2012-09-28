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
class AddressGeolocation
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
}
