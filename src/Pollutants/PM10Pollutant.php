<?php

namespace AirQuality\Pollutants;

use AirQuality\AirQualityIndex;

class PM10Pollutant extends GenericPollutant
{
    protected $precision = 0;

    protected $breakpoints = [
        AirQualityIndex::GOOD                => [0, 54],
        AirQualityIndex::MODERATE            => [55, 154],
        AirQualityIndex::UNHEALTHY_SENSITIVE => [155, 254],
        AirQualityIndex::UNHEALTHY           => [255, 354],
        AirQualityIndex::VERY_UNHEALTHY      => [355, 424],
        AirQualityIndex::HAZARDOUS           => [425, 504],
        AirQualityIndex::VERY_HAZARDOUS      => [505, 604],
    ];
}
