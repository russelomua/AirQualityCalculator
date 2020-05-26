<?php

namespace AirQuality\Pollutants;

use AirQuality\AirQualityIndex;

class Ozone1hPollutant extends GenericPollutant
{
    protected $precision = 3;

    protected $breakpoints = [
        AirQualityIndex::UNHEALTHY_SENSITIVE => [0.125, 0.164],
        AirQualityIndex::UNHEALTHY           => [0.165, 0.204],
        AirQualityIndex::VERY_UNHEALTHY      => [0.205, 0.404],
        AirQualityIndex::HAZARDOUS           => [0.405, 0.504],
        AirQualityIndex::VERY_HAZARDOUS      => [0.505, 0.604],
    ];
}
