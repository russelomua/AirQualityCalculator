<?php

namespace AirQuality\Pollutants;

use AirQuality\AirQualityIndex;

class SO2Pollutant extends GenericPollutant
{
    protected $precision = 0;

    protected $breakpoints = [
        AirQualityIndex::GOOD                => [0, 35],
        AirQualityIndex::MODERATE            => [36, 75],
        AirQualityIndex::UNHEALTHY_SENSITIVE => [76, 185],
        AirQualityIndex::UNHEALTHY           => [186, 304],
        AirQualityIndex::VERY_UNHEALTHY      => [305, 604],
        AirQualityIndex::HAZARDOUS           => [605, 804],
        AirQualityIndex::VERY_HAZARDOUS      => [805, 1004],
    ];
}
