<?php
declare(strict_types=1);

namespace AirQuality\Pollutants;

use AirQuality\Category;
use AirQuality\ConcentrationCategory;
use function PHPUnit\Framework\matches;

final class NO2Pollutant implements PollutantInterface
{
    public function truncateNowCast(float|int $nowCast): int
    {
        return (int) round($nowCast);
    }

    public function getConcentration(Category $index): ?ConcentrationCategory
    {
        return match ($index) {
            Category::GOOD                => new ConcentrationCategory(0, 53),
            Category::MODERATE            => new ConcentrationCategory(54, 100),
            Category::UNHEALTHY_SENSITIVE => new ConcentrationCategory(101, 360),
            Category::UNHEALTHY           => new ConcentrationCategory(361, 649),
            Category::VERY_UNHEALTHY      => new ConcentrationCategory(650, 1249),
            Category::HAZARDOUS           => new ConcentrationCategory(1250, 1649),
            Category::VERY_HAZARDOUS      => new ConcentrationCategory(1650, 2049),
        };
    }
}
