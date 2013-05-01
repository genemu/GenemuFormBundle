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
    private $street_number;
    private $route;
    private $postalcode;
    private $admin_area_level2;
    private $admin_area_level1;           
    private $latitude;
    private $longitude;
    private $locality;
    private $country;

    public function __construct(
            $address, $latitude = null, $longitude = null, $locality = null, $country = null, $street_number = null, $route = null, $postal_code = null, $admin_area_level2 = null, $admin_area_level1 = null)
    {
        $this->address = $address;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->locality = $locality;
        $this->country = $country;
        $this->street_number = $street_number;
        $this->route = $route;
        $this->admin_area_level1 = $admin_area_level1;
        $this->admin_area_level2 = $admin_area_level2;
        $this->postal_code = $postal_code;
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
    
    public function getPostalCode()
    {
        return $this->postal_code;
    }
    
    public function getStreetNumber()
    {
        return $this->street_number;
    }
    
    public function getRoute()
    {
        return $this->route;
    }
    
    public function getAdminAreaLevel2()
    {
        return $this->admin_area_level2;
    }
    
    public function getAdminAreaLevel1()
    {
        return $this->admin_area_level1;
    }
}
