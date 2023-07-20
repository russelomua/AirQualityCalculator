<?php

namespace Tests;

use AirQuality\Category;
use AirQuality\Pollutants\PollutantInterface;

trait PollutantTrait
{
    public function createPollutant(int $precision, array $breakpoints): PollutantInterface
    {
        $pollutant = $this->getMockBuilder(PollutantInterface::class)->getMock();
        $pollutant->method('truncateNowCast')->willReturnCallback(
            static function (float $nowCast) use ($precision) {
                return round($nowCast, $precision);
            },
        );
        $pollutant->method('getConcentration')->willReturnCallback(
            static function (Category $index) use ($breakpoints) {
                return $breakpoints[$index->value] ?? $breakpoints[0];
            },
        );

        return $pollutant;
    }
}
