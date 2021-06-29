<?php

namespace App\Entity;

include_once 'WeatherData.php';
include_once 'AddressData.php';

class WeatherApi
{
    public function getAddressData(string $address): AddressData
    {
        $config = self::getConfig();
        $url = $config['api_geo_url'];
        $key = $config['api_geo_key'];

        $params = [
            'geocode' => $address,
            'apikey' => $key,
            'format' => 'json',
            'results' => 1,
        ];

        $requestUrl = "$url?" . http_build_query($params);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $requestUrl);

        $result = curl_exec($ch);
        curl_close($ch);
        $json = json_decode($result, true);

        return new AddressData($json);
    }

    public function getWeatherInPlace(float $lat, float $lng): array
    {
        $config = self::getConfig();
        $url = $config['api_weather_url'];
        $key = $config['api_weather_key'];

        $params = [
            'lat' => $lat,
            'lon' => $lng,
            'extra' => true,
            'lang' => 'ru_RU',
            'limit' => 7,
            'hours' => true,
        ];

        $header = "X-Yandex-API-Key: " . $key;

        $requestUrl = "$url?" . http_build_query($params);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $requestUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array($header));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        $result = curl_exec($ch);
        curl_close($ch);
        $json = json_decode($result, true);

        $result = [];

        foreach ($json['forecasts'] as $forecast) {
            $date = $forecast['date'];
            foreach ($forecast['hours'] as $hourWeather) {
                $weatherData = [];
                $hour = $hourWeather['hour'];
                $temp = $hourWeather['temp'];
                $feelsLikeTemp = $hourWeather['feels_like'];

                $weatherData['date'] = $date;
                $weatherData['hour'] = $hour;
                $weatherData['real_temp'] = $temp;
                $weatherData['feels_like_temp'] = $feelsLikeTemp;

                $weather = new WeatherData($weatherData);
                $result[] = $weather->getDataArray();
            }
        }

        return $result;
    }

    /**
     * Метод для получения массива с конфигурацией
     *
     * @return mixed (массив с конфигурацией: URL и ключ доступа)
     */
    private static function getConfig()
    {
        return [
            'api_geo_url' => 'https://geocode-maps.yandex.ru/1.x',
            'api_geo_key' => '',
            'api_weather_url' => 'https://api.weather.yandex.ru/v2/forecast',
            'api_weather_key' => '',
        ];
    }
}