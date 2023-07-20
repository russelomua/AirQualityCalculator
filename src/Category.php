<?php
declare(strict_types=1);

namespace AirQuality;

enum Category: int
{
    case GOOD = 0;
    case MODERATE = 1;
    case UNHEALTHY_SENSITIVE = 2;
    case UNHEALTHY = 3;
    case VERY_UNHEALTHY = 4;
    case HAZARDOUS = 5;
    case VERY_HAZARDOUS = 6;

    public function getLowIndex(): int
    {
        return match ($this) {
            self::GOOD                => 0,
            self::MODERATE            => 51,
            self::UNHEALTHY_SENSITIVE => 101,
            self::UNHEALTHY           => 151,
            self::VERY_UNHEALTHY      => 201,
            self::HAZARDOUS           => 301,
            self::VERY_HAZARDOUS      => 400,
        };
    }

    public function getHighIndex(): int
    {
        return match ($this) {
            self::GOOD                => 50,
            self::MODERATE            => 100,
            self::UNHEALTHY_SENSITIVE => 150,
            self::UNHEALTHY           => 200,
            self::VERY_UNHEALTHY      => 300,
            self::HAZARDOUS           => 400,
            self::VERY_HAZARDOUS      => 500,
        };
    }

    public function getHexColor(): string
    {
        return match ($this) {
            self::GOOD                            => '#00e400',
            self::MODERATE                        => '#ffff00',
            self::UNHEALTHY_SENSITIVE             => '#ff7e00',
            self::UNHEALTHY                       => '#ff0000',
            self::VERY_UNHEALTHY                  => '#99004c',
            self::HAZARDOUS, self::VERY_HAZARDOUS => '#7e0023',
        };
    }

    public function getName(): string
    {
        return strtolower($this->name);
    }
}
