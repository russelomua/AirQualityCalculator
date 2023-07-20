<?php

namespace Tests;

use AirQuality\Category;
use AirQuality\Index;
use AirQuality\Quality;
use PHPUnit\Framework\TestCase;

class JsonSerializeTest extends TestCase
{
    public function testIndex(): void
    {
        $index = new Index(10, Category::GOOD);
        $expect = [
            'index'        => 10,
            'category'     => 0,
            'categoryName' => 'good',
        ];

        self::assertSame($expect, $index->jsonSerialize());
    }

    public function testNullIndex(): void
    {
        $index = new Index(null, null);
        $expect = [
            'index'        => null,
            'category'     => null,
            'categoryName' => null,
        ];

        self::assertSame($expect, $index->jsonSerialize());
    }

    public function testQuality(): void
    {
        $quality = new Quality(1.2, new Index(500, Category::VERY_HAZARDOUS));
        $expect = [
            'nowCast'      => 1.2,
            'index'        => 500,
            'category'     => 6,
            'categoryName' => 'very_hazardous',
        ];

        self::assertSame($expect, $quality->jsonSerialize());
    }

    public function testNullQuality(): void
    {
        $quality = new Quality(null, new Index(null, null));
        $expect = [
            'nowCast'      => null,
            'index'        => null,
            'category'     => null,
            'categoryName' => null,
        ];

        self::assertSame($expect, $quality->jsonSerialize());
    }
}
