<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Psr\Log\LoggerInterface;

class LatLongService implements LatLongInterface
{
    public HttpClientInterface $client;
    public $accessKey;
    public LoggerInterface $logger;

    public function __construct(HttpClientInterface $client, $positionApiKey, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->accessKey = $positionApiKey;
        $this->logger = $logger;
    }

    /**
     * @throws \Exception
     */
    public function generateLatLong($address)
    {
        try {
            $response = $this->client->request(
                'GET',
                'http://api.positionstack.com/v1/forward?access_key=' . $this->accessKey . '&query=' . $address
            );
            $response = $response->toArray();
            if(array_key_exists('data', $response)){
                return $response['data'][0];
            }
        }catch (\Exception $e)
        {
            $this->logger->error('An error occurred: ' . $e->getMessage());
            throw new \Exception('Unable to pull lat long data for the given address');
        }
    }
}