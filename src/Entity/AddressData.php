<?php


namespace App\Entity;


class AddressData
{
    private string $fullAddress;
    private float $latitude;
    private float $longitude;

    /**
     * Конструктор сущности "Адрес"
     *
     * @param array $data (массив с полученным ответом от сервиса Яндекса)
     */
    public function __construct(array $data)
    {
        $this->setFullAddress($data);
        $this->setLatitude($data);
        $this->setLongitude($data);
    }

    /**
     * Метод для указания полного адреса
     *
     * @param array $data (массив с полученными данными из GeoCoder)
     */
    public function setFullAddress(array $data): void
    {
        $currentAddress = $data['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['metaDataProperty']['GeocoderMetaData']['Address']['formatted'];
        if (isset($currentAddress)) {
            $this->fullAddress = $currentAddress;
        }
    }

    /**
     * Метод для указания новой широты
     *
     * @param array $data (исходный массив полученный от сервиса Яндекса)
     */
    public function setLatitude(array $data): void
    {
        $currentPosition = $data['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['Point']['pos'];
        if (isset($currentPosition)) {
            $latitude = explode(' ', $currentPosition)[0];
            $this->latitude = $latitude;
        }
    }

    /**
     * Метод для указания новой долготы
     *
     * @param array $data (исходный массив полученный от сервиса Яндекса)
     */
    public function setLongitude(array $data): void
    {
        $currentPosition = $data['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['Point']['pos'];
        if (isset($currentPosition)) {
            $longitude = explode(' ', $currentPosition)[1];
            $this->longitude = $longitude;
        }
    }

    /**
     * Метод для получения данных текущей сущности в виде массива
     *
     * @return array (ассоциативный массив с информацией о текущей сущности)
     */
    public function getDataArray(): array
    {
        $result = [];
        $result['full_address'] = $this->fullAddress;
        $result['latitude'] = $this->latitude;
        $result['longitude'] = $this->longitude;
        return $result;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function getFullAddress(): string
    {
        return $this->fullAddress ?? "";
    }
}