<?php

namespace AirQuality\Pollutants;

use AirQuality\AirQualityIndex;

class NO2Pollutant extends GenericPollutant
{
    protected $precision = 0;

    protected $breakpoints = [
        AirQualityIndex::GOOD                => [0, 53],
        AirQualityIndex::MODERATE            => [54, 100],
        AirQualityIndex::UNHEALTHY_SENSITIVE => [101, 360],
        AirQualityIndex::UNHEALTHY           => [361, 649],
        AirQualityIndex::VERY_UNHEALTHY      => [650, 1249],
        AirQualityIndex::HAZARDOUS           => [1250, 1649],
        AirQualityIndex::VERY_HAZARDOUS      => [1650, 2049],
    ];
}
