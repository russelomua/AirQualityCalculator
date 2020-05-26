<?php

namespace AirQuality\Pollutants;

use AirQuality\AirQualityIndex;

class Ozone8hPollutant extends GenericPollutant
{
    protected $precision = 3;

    protected $breakpoints = [
        AirQualityIndex::GOOD                => [0, 0.054],
        AirQualityIndex::MODERATE            => [0.055, 0.070],
        AirQualityIndex::UNHEALTHY_SENSITIVE => [0.071, 0.085],
        AirQualityIndex::UNHEALTHY           => [0.086, 0.105],
        AirQualityIndex::VERY_UNHEALTHY      => [0.106, 0.200],
    ];
}
