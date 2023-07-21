<?php
declare(strict_types=1);

namespace AirQuality\Pollutants;

use AirQuality\Category;
use AirQuality\ConcentrationCategory;

final class COPollutant implements PollutantInterface
{
    public function truncateNowCast(float|int $nowCast): float
    {
        return round($nowCast, 1);
    }

    public function getConcentration(Category $index): ?ConcentrationCategory
    {
        return match ($index) {
            Category::GOOD                => new ConcentrationCategory(0, 4.4),
            Category::MODERATE            => new ConcentrationCategory(4.5, 9.4),
            Category::UNHEALTHY_SENSITIVE => new ConcentrationCategory(9.5, 12.4),
            Category::UNHEALTHY           => new ConcentrationCategory(12.5, 15.4),
            Category::VERY_UNHEALTHY      => new ConcentrationCategory(15.5, 30.4),
            Category::HAZARDOUS           => new ConcentrationCategory(30.5, 40.4),
            Category::VERY_HAZARDOUS      => new ConcentrationCategory(40.5, 50.4),
        };
    }
}
