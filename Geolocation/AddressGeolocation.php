<?php

/*
 * This file is part of the Symfony package.
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

    /**
     * Constructs
     *
     * @param string      $address
     * @param null|string $latitude
     * @param null|string $longitude
     * @param null|string $locality
     * @param null|string $country
     */
    public function __construct($address, $latitude = null, $longitude = null, $locality = null, $country = null)
    {
        $this->address = $address;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->locality = $locality;
        $this->country = $country;
    }

    /**
     * Gets address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Gets latitude
     *
     * @return null|string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Gets longitude
     *
     * @return null|string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Gets locality
     *
     * @return null|string
     */
    public function getLocality()
    {
        return $this->locality;
    }

    /**
     * Gets country
     *
     * @return null|string
     */
    public function getCountry()
    {
        return $this->country;
    }
}
