<?php
declare(strict_types=1);

namespace AirQuality\Pollutants;

use AirQuality\Category;
use AirQuality\ConcentrationCategory;

final class Ozone8hPollutant implements PollutantInterface
{
    public function truncateNowCast(float|int $nowCast): float
    {
        return round($nowCast, 3);
    }

    public function getConcentration(Category $index): ?ConcentrationCategory
    {
        return match ($index) {
            Category::GOOD                => new ConcentrationCategory(0, 0.054),
            Category::MODERATE            => new ConcentrationCategory(0.055, 0.070),
            Category::UNHEALTHY_SENSITIVE => new ConcentrationCategory(0.071, 0.085),
            Category::UNHEALTHY           => new ConcentrationCategory(0.086, 0.105),
            Category::VERY_UNHEALTHY      => new ConcentrationCategory(0.106, 0.200),
            default                       => null,
        };
    }
}
