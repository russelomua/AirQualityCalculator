<?php
declare(strict_types=1);

namespace AirQuality\Pollutants;

use AirQuality\Category;
use AirQuality\ConcentrationCategory;

final class PM10Pollutant implements PollutantInterface
{
    public function truncateNowCast(float|int $nowCast): int
    {
        return (int) round($nowCast);
    }

    public function getConcentration(Category $index): ?ConcentrationCategory
    {
        return match ($index) {
            Category::GOOD                => new ConcentrationCategory(0, 54),
            Category::MODERATE            => new ConcentrationCategory(55, 154),
            Category::UNHEALTHY_SENSITIVE => new ConcentrationCategory(155, 254),
            Category::UNHEALTHY           => new ConcentrationCategory(255, 354),
            Category::VERY_UNHEALTHY      => new ConcentrationCategory(355, 424),
            Category::HAZARDOUS           => new ConcentrationCategory(425, 504),
            Category::VERY_HAZARDOUS      => new ConcentrationCategory(505, 604),
        };
    }
}
