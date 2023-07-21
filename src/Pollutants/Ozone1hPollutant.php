<?php
declare(strict_types=1);

namespace AirQuality\Pollutants;

use AirQuality\Category;
use AirQuality\ConcentrationCategory;

final class Ozone1hPollutant implements PollutantInterface
{
    public function truncateNowCast(float|int $nowCast): float
    {
        return round($nowCast, 3);
    }

    public function getConcentration(Category $index): ?ConcentrationCategory
    {
        return match ($index) {
            default                       => null,
            Category::UNHEALTHY_SENSITIVE => new ConcentrationCategory(0.125, 0.164),
            Category::UNHEALTHY           => new ConcentrationCategory(0.165, 0.204),
            Category::VERY_UNHEALTHY      => new ConcentrationCategory(0.205, 0.404),
            Category::HAZARDOUS           => new ConcentrationCategory(0.405, 0.504),
            Category::VERY_HAZARDOUS      => new ConcentrationCategory(0.505, 0.604),
        };
    }
}
