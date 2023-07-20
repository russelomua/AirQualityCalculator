<?php
declare(strict_types=1);

namespace AirQuality\Pollutants;

use AirQuality\Category;
use AirQuality\ConcentrationCategory;

readonly class PM25Pollutant implements PollutantInterface
{
    public function truncateNowCast(float|int $nowCast): float
    {
        return round($nowCast, 1);
    }

    public function getConcentration(Category $index): ?ConcentrationCategory
    {
        return match ($index) {
            Category::GOOD                => new ConcentrationCategory(0, 12),
            Category::MODERATE            => new ConcentrationCategory(12.1, 35.4),
            Category::UNHEALTHY_SENSITIVE => new ConcentrationCategory(35.5, 55.4),
            Category::UNHEALTHY           => new ConcentrationCategory(55.5, 150.4),
            Category::VERY_UNHEALTHY      => new ConcentrationCategory(150.5, 250.4),
            Category::HAZARDOUS           => new ConcentrationCategory(250.5, 350.4),
            Category::VERY_HAZARDOUS      => new ConcentrationCategory(350.5, 500.4),
        };
    }
}
