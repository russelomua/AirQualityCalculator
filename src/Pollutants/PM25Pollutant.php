<?php

namespace AirQuality\Pollutants;

use AirQuality\AirQualityIndex;

class PM25Pollutant extends GenericPollutant
{
    protected $breakpoints = [
        AirQualityIndex::GOOD                => [0, 12],
        AirQualityIndex::MODERATE            => [12.1, 35.4],
        AirQualityIndex::UNHEALTHY_SENSITIVE => [35.5, 55.4],
        AirQualityIndex::UNHEALTHY           => [55.5, 150.4],
        AirQualityIndex::VERY_UNHEALTHY      => [150.5, 250.4],
        AirQualityIndex::HAZARDOUS           => [250.5, 350.4],
        AirQualityIndex::VERY_HAZARDOUS      => [350.5, 500.4],
    ];

    protected $precision = 1;
}
