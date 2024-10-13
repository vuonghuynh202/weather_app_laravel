<?php
namespace App\Services;

use GuzzleHttp\Client;

class WeatherService {
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = '8077d0c289f74facbaf42523241210';
    }

    public function getWeather($location)
    {
        $currentResponse = $this->client->get("https://api.weatherapi.com/v1/current.json", [
            'query' => [
                'key' => $this->apiKey,
                'q' => $location,
                'aqi' => 'no'
            ]
        ]);

        $forecastResponse = $this->client->get("https://api.weatherapi.com/v1/forecast.json", [
            'query' => [
                'key' => $this->apiKey,
                'q' => $location,
                'days' => 10,
                'aqi' => 'no',
                'alerts' => 'no'
            ]
        ]);

        return [
            'current' => json_decode($currentResponse->getBody(), true),
            'forecast' => json_decode($forecastResponse->getBody(), true),
        ];
    }
}