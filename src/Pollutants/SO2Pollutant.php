<?php
declare(strict_types=1);

namespace AirQuality\Pollutants;

use AirQuality\Category;
use AirQuality\ConcentrationCategory;

final class SO2Pollutant implements PollutantInterface
{
    public function truncateNowCast(float|int $nowCast): int
    {
        return (int) round($nowCast);
    }

    public function getConcentration(Category $index): ?ConcentrationCategory
    {
        return match ($index) {
            Category::GOOD                => new ConcentrationCategory(0, 35),
            Category::MODERATE            => new ConcentrationCategory(36, 75),
            Category::UNHEALTHY_SENSITIVE => new ConcentrationCategory(76, 185),
            Category::UNHEALTHY           => new ConcentrationCategory(186, 304),
            Category::VERY_UNHEALTHY      => new ConcentrationCategory(305, 604),
            Category::HAZARDOUS           => new ConcentrationCategory(605, 804),
            Category::VERY_HAZARDOUS      => new ConcentrationCategory(805, 1004),
        };
    }
}
