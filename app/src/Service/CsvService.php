<?php

namespace App\Service;

class CsvService
{
    private string $projectDir;

    public function __construct(string $projectDir)
    {
        $this->projectDir = $projectDir;
    }

    public function saveCsvFile($response)
    {
        // Open a file in write mode ('w')
        $fp = fopen($this->projectDir.'/distances.csv', 'w');

        fputcsv($fp, ['Sort Number','Distance', 'Name', 'Address']);

        foreach ($response as $key => $data) {
            $sort = ['sortnumber' => $key+1];
            $data = $sort+$data;
            $data['distance'] = $data['distance'].' km';
            fputcsv($fp, $data);
        }

        fclose($fp);

    }

}