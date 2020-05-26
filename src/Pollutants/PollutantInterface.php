<?php

namespace AirQuality\Pollutants;

interface PollutantInterface
{
    /**
     * @param int|float $roundedNowCast
     *
     * @return int
     */
    public function getAirQualityCategory($roundedNowCast);

    /**
     * @param int|float $nowCast
     *
     * @return int|float
     */
    public function truncateNowCast($nowCast);

    /**
     * @param $category
     *
     * @return int|float
     */
    public function getLowBreakpoint($category);

    /**
     * @param $category
     *
     * @return int|float
     */
    public function getHighBreakpoint($category);
}
