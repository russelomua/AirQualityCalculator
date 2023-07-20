<?php
declare(strict_types=1);

namespace AirQuality\Pollutants;

use AirQuality\Category;
use AirQuality\ConcentrationCategory;

interface PollutantInterface
{
    public function truncateNowCast(float|int $nowCast): float|int;

    public function getConcentration(Category $index): ?ConcentrationCategory;
}
