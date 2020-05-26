<?php

namespace AirQuality\Pollutants;

class PollutantsFactory
{
    public const PM25 = 0;
    public const PM10 = 1;
    public const CO = 2;
    public const SO2 = 3;
    public const NO2 = 4;
    public const OZONE_8H = 5;
    public const OZONE_1H = 6;

    private $associations = [
        self::PM25 => PM25Pollutant::class,
        self::PM10 => PM10Pollutant::class,
        self::CO => COPollutant::class,
        self::SO2 => SO2Pollutant::class,
        self::NO2 => NO2Pollutant::class,
        self::OZONE_8H => Ozone8hPollutant::class,
        self::OZONE_1H => Ozone1hPollutant::class,
    ];

    /**
     * @param $pollutant
     *
     * @return PollutantInterface
     */
    public function create($pollutant)
    {
        $className = $this->getClassname($pollutant);

        return new $className();
    }

    /**
     * @param int $pollutant
     *
     * @return string
     */
    private function getClassname($pollutant)
    {
        if (!is_int($pollutant) || !array_key_exists($pollutant, $this->associations)) {
            throw new \UnexpectedValueException('Unsupported pollutant');
        }

        return $this->associations[$pollutant];
    }
}
