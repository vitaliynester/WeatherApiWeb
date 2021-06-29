<?php

use App\Entity\WeatherApi;

include_once './Entity/WeatherApi.php';

$api = new WeatherApi();
$address = $api->getAddressData($_POST['address']);
if ($address->getFullAddress() == "") {
    echo json_encode(['msg' => 'Невозможно получить прогноз погоды в указанном месте!']);
    die();
}
$weather = $api->getWeatherInPlace($address->getLatitude(), $address->getLongitude());
$result = [];
$result['weather'] = $weather;
$result['address'] = $address->getFullAddress();
echo json_encode($result);
