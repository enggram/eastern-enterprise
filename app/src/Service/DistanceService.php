<?php

namespace App\Service;

use App\Service\LatLongInterface;

class DistanceService
{
    public LatLongInterface $latLongService;
    public string $projectDir;
    public $hqAddress;
    public $otherAddresses;
    public CsvService $csvService;

    public function __construct(LatLongInterface $latLongInterface, string $projectDir, CsvService $csvService)
    {
        $this->latLongService = $latLongInterface;
        $this->projectDir = $projectDir;
        $this->csvService = $csvService;
    }

    /**
     * Get distance from two coordinates and return response, also store it in a csv file
     * located in root folder - file name distances.csv
     * @return array
     */
    public function getDistance()
    {
        $this->getAddresses();
        $hqData = $this->latLongService->generateLatLong($this->hqAddress[1]);
        $hqLatLong['lat'] = $hqData['latitude'];
        $hqLatLong['long'] = $hqData['longitude'];
        $response = [];

        foreach($this->otherAddresses as $key => $address)
        {
            $latLongData = $this->latLongService->generateLatLong($address[1]);
            $latitude = $latLongData['latitude'];
            $longitude = $latLongData['longitude'];
            $distance = $this->calculateDistance($hqLatLong['lat'], $hqLatLong['long'], $latitude, $longitude);
            $response[$key]['distance'] = round($distance,2);
            $response[$key]['name'] = $address[0];
            $response[$key]['address'] = $address[1];
        }

        usort($response, function ($item1, $item2) {
            return $item1['distance'] <=> $item2['distance'];
        });

        /**
         * Store response in a csv file stored in root folder
         */
        $this->csvService->saveCsvFile($response);

        return $response;

    }

    /**
     * Get Address from the csv file stored in the root folder, the first
     * line will be the HQ address. File stored in root folder as addresses.csv
     * @return void
     */
    public function getAddresses()
    {
        $csvFile = file($this->projectDir.'/addresses.csv');
        $data = [];
        $i=0;
        foreach ($csvFile as $line) {
            if($i==0){
                $this->hqAddress = str_getcsv($line);
            }else {
                $data[] = str_getcsv($line);
            }
            $i++;
        }
        $this->otherAddresses = $data;
    }

    /**
     * Calculate distance between two points using trigonometry to measure curvature
     * @param $lat1
     * @param $lon1
     * @param $lat2
     * @param $lon2
     * @param $unit
     * @return float|int
     */
    public function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        } else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            return ($miles * 1.609344);
        }
    }

}