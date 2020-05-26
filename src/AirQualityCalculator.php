<?php

namespace AirQuality;

use AirQuality\Pollutants\PollutantInterface;

/**
 * Class AirQualityCalculator
 * Calculates AQI
 *
 * @package AirQualityCalculator
 */
class AirQualityCalculator
{
    /** @var AirQualityIndex */
    private $airQualityIndex;

    private function getAirQualityIndex()
    {
        if (!$this->airQualityIndex) {
            $this->airQualityIndex = new AirQualityIndex();
        }

        return $this->airQualityIndex;
    }

    /**
     * @param array              $hourlyConcentrations
     * @param PollutantInterface $pollutant
     *
     * @return AirQualityDTO
     */
    public function getAirQuality($hourlyConcentrations, PollutantInterface $pollutant)
    {
        $dto = new AirQualityDTO();

        if ($nowCast = $this->calculateNowCast($hourlyConcentrations)) {
            $dto->nowCast = $nowCast;
            $dto->category = $pollutant->getAirQualityCategory($nowCast);
            $dto->index = $this->calculateAQI($nowCast, $pollutant);
        }

        return $dto;
    }


    /**
     * @param float              $nowCast
     * @param PollutantInterface $pollutant
     *
     * @return int|null
     */
    public function calculateAQI($nowCast, PollutantInterface $pollutant)
    {
        if (!$nowCast) {
            return null;
        }

        $category = $pollutant->getAirQualityCategory($nowCast);

        $lowConcentration = $pollutant->getLowBreakpoint($category);
        $highConcentration = $pollutant->getHighBreakpoint($category);

        $lowIndex = $this->getAirQualityIndex()->getLowIndex($category);
        $highIndex = $this->getAirQualityIndex()->getHighIndex($category);

        $aqi = (($nowCast - $lowConcentration) / ($highConcentration - $lowConcentration)) * ($highIndex - $lowIndex) + $lowIndex;

        return (int)round($aqi);
    }

    /**
     * The hourly PM concentrations for the most recent 12-hour period
     * Where 0 index is the most recent hourly value
     *
     *
     * @param int[] $hourlyConcentrations [ now, -1 hour, -2 hour, etc... ]
     *
     * @return float|null
     */
    public function calculateNowCast($hourlyConcentrations)
    {
        if (!$this->isConcentrationsValid($hourlyConcentrations)) {
            return null;
        }

        $minConcentration = min($hourlyConcentrations);
        $maxConcentration = max($hourlyConcentrations);

        $scaledRate = ($maxConcentration - $minConcentration) / $maxConcentration;

        $weightFactor = (1 - $scaledRate) > 0.5 ? $scaledRate : 0.5;

        $nowCastUp = 0;
        $nowCastDown = 0;

        foreach ($hourlyConcentrations as $i => $concentration) {
            if ($concentration > 0) {
                $nowCastUp += ($weightFactor ** $i) * $concentration;
                $nowCastDown += $weightFactor ** $i;
            }
        }

        return round($nowCastUp / $nowCastDown, 1);
    }

    /**
     * @param $hourlyConcentrations
     *
     * @return bool
     */
    private function isConcentrationsValid($hourlyConcentrations)
    {
        if (!is_array($hourlyConcentrations) || count($hourlyConcentrations) < 2) {
            return false;
        }

        return $this->isAtLeastTwoOfTreeFirst($hourlyConcentrations);
    }

    private function isAtLeastTwoOfTreeFirst($hourlyConcentrations)
    {
        $concentrationsCount = count($hourlyConcentrations);
        $limit = $concentrationsCount >= 3 ? 3 : $concentrationsCount;

        $count = 0;
        for ($i = 0; $i < $limit; $i++) {
            is_numeric($hourlyConcentrations[$i]) && $hourlyConcentrations[$i] > 0 && $count++;
        }

        return !($count < 2);
    }

}
