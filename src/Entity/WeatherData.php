<?php


namespace App\Entity;


class WeatherData
{
    private string $date;
    private string $hour;
    private float $realTemp;
    private float $feelsLikeTemp;

    public function __construct(array $data)
    {
        $this->setDate($data['date']);
        $this->setHour($data['hour']);
        $this->setRealTemp($data['real_temp']);
        $this->setFeelsLikeTemp($data['feels_like_temp']);
    }

    /**
     * @param string $hour
     */
    public function setHour(string $hour): void
    {
        if (strlen($hour) == 1) {
            $this->hour = "0$hour:00";
        } else {
            $this->hour = "$hour:00";
        }
    }

    /**
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * @param string $date
     */
    public function setDate(string $date): void
    {
        $this->date = $date;
    }

    /**
     * @return float
     */
    public function getRealTemp(): float
    {
        return $this->realTemp;
    }

    /**
     * @param float $realTemp
     */
    public function setRealTemp(float $realTemp): void
    {
        $this->realTemp = $realTemp;
    }

    /**
     * @return float
     */
    public function getFeelsLikeTemp(): float
    {
        return $this->feelsLikeTemp;
    }

    /**
     * @param float $feelsLikeTemp
     */
    public function setFeelsLikeTemp(float $feelsLikeTemp): void
    {
        $this->feelsLikeTemp = $feelsLikeTemp;
    }

    public function getDataArray(): array
    {
        $result = [];
        $result['date'] = $this->date . " " . $this->hour;
        $result['real_temp'] = $this->realTemp;
        $result['feels_like_temp'] = $this->feelsLikeTemp;
        return $result;
    }
}