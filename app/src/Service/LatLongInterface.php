<?php

namespace App\Service;

interface LatLongInterface
{
    /**
     * Generate Latitude and Longitude
     * @param $address
     * @return mixed
     */
    public function generateLatLong($address);
}