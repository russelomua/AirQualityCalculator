<?php
declare(strict_types=1);

namespace AirQuality;

/**
 * Class AirQualityCalculator
 * Calculates AQI
 *
 * @package AirQualityCalculator
 */
final class Calculator
{
    public function __construct(
        private readonly NowCast $nowCast,
        private readonly ConcentrationCategoryResolver $concentrationResolver,
    ) {
    }

    public function getQuality(): Quality
    {
        return new Quality(
            $this->nowCast->getValue(),
            $this->getIndex()
        );
    }

    public function getIndex(): Index
    {
        $nowCast = $this->nowCast->getValue();
        $concentration = $this->concentrationResolver->get($nowCast);

        if (null === $nowCast || null === $concentration) {
            return new Index();
        }

        $category = $concentration->getCategory();
        $lowIndex = $category->getLowIndex();
        $highIndex = $category->getHighIndex();

        $aqi = (($nowCast - $concentration->low) / ($concentration->high - $concentration->low)) * ($highIndex - $lowIndex) + $lowIndex;

        return new Index((int) round($aqi), $category);
    }
}
