<?php

namespace AirQuality;

/**
 * Class AirQualityIndex
 *
 * @package AirQualityCalculator
 */
class AirQualityIndex
{
    public const GOOD = 0;
    public const MODERATE = 1;
    public const UNHEALTHY_SENSITIVE = 2;
    public const UNHEALTHY = 3;
    public const VERY_UNHEALTHY = 4;
    public const HAZARDOUS = 5;
    public const VERY_HAZARDOUS = 6;

    private $aqi = [
        self::GOOD                => [0, 50],
        self::MODERATE            => [51, 100],
        self::UNHEALTHY_SENSITIVE => [101, 150],
        self::UNHEALTHY           => [151, 200],
        self::VERY_UNHEALTHY      => [201, 300],
        self::HAZARDOUS           => [301, 400],
        self::VERY_HAZARDOUS      => [400, 500],
    ];

    /**
     * @param int $category
     *
     * @return int
     */
    public function getLowIndex($category)
    {
        if (!array_key_exists($category, $this->aqi)) {
            throw new \UnexpectedValueException('Unexpected air quality index category');
        }
        return $this->aqi[$category][0];
    }

    /**
     * @param int $category
     *
     * @return int
     */
    public function getHighIndex($category)
    {
        if (!array_key_exists($category, $this->aqi)) {
            throw new \UnexpectedValueException('Unexpected air quality index category');
        }
        return $this->aqi[$category][1];
    }
}
