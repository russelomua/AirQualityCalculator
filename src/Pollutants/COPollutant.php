<?php

namespace AirQuality\Pollutants;

use AirQuality\AirQualityIndex;

class COPollutant extends GenericPollutant
{
    protected $precision = 1;

    protected $breakpoints = [
        AirQualityIndex::GOOD                => [0, 4.4],
        AirQualityIndex::MODERATE            => [4.5, 9.4],
        AirQualityIndex::UNHEALTHY_SENSITIVE => [9.5, 12.4],
        AirQualityIndex::UNHEALTHY           => [12.5, 15.4],
        AirQualityIndex::VERY_UNHEALTHY      => [15.5, 30.4],
        AirQualityIndex::HAZARDOUS           => [30.5, 40.4],
        AirQualityIndex::VERY_HAZARDOUS      => [40.5, 50.4],
    ];
}
