<?php

namespace AirQuality;

use AirQuality\Pollutants\PollutantInterface;

class ConcentrationCategoryResolver
{
    public function __construct(private readonly PollutantInterface $pollutant) { }

    public function get(?float $nowCast): ?ConcentrationCategory
    {
        if (null === $nowCast) {
            return null;
        }

        $truncatedNowCast = $this->pollutant->truncateNowCast($nowCast);

        foreach (Category::cases() as $category) {
            $concentration = $this->pollutant->getConcentration($category);

            if ($concentration && $concentration->inRange($truncatedNowCast)) {
                return $concentration->withCategory($category);
            }
        }

        return null;
    }
}
