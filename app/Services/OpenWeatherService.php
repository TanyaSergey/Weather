<?php

namespace App\Services;

use GuzzleHttp\Client;

class OpenWeatherService
{
    private $httpClient;

    public function __construct()
    {
        $this->httpClient = new Client(['base_uri' => 'https://api.openweathermap.org/data/2.5/']);
    }

    public function getWeatherByGeoName(string $name)
    {
        try {
            $response = $this->httpClient->get('weather?q='.$name.'&appid='.config('app.open_weather_api_key'));
            return json_decode($response->getBody()->getContents());
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getWeatherByGeoCoordinates(float $lat, float $long)
    {
        try {
            $response = $this->httpClient->get('weather?lat=' . $lat . '&lon=' . $long . '&appid='.config('app.open_weather_api_key'));
            return json_decode($response->getBody()->getContents());
        } catch (\Exception $e) {
            return null;
        }
    }
}