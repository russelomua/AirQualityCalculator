<?php

namespace Tests;

use AirQuality\Category;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    public static function categoriesDataProvider(): iterable
    {
        foreach (Category::cases() as $category) {
            yield $category->name => [$category];
        }
    }

    /**
     * @covers \AirQuality\Category::getLowIndex
     * @covers \AirQuality\Category::getHighIndex
     */
    #[DataProvider('categoriesDataProvider')]
    public function testGetLowIndex(Category $category): void
    {
        $expect = [
            [0, 50], // GOOD
            [51, 100], // self::MODERATE
            [101, 150], // self::UNHEALTHY_SENSITIVE
            [151, 200], // self::UNHEALTHY
            [201, 300], // self::VERY_UNHEALTHY
            [301, 400], // self::HAZARDOUS
            [400, 500], // self::VERY_HAZARDOUS
        ];

        $this->assertSame($expect[$category->value], [$category->getLowIndex(), $category->getHighIndex()]);
    }

    /**
     * @covers \AirQuality\Category::getHexColor
     */
    #[DataProvider('categoriesDataProvider')]
    public function testHexColor(Category $category): void
    {
        self::assertStringStartsWith('#', $category->getHexColor());
        self::assertSame(7, strlen($category->getHexColor()));
    }

    /**
     * @covers \AirQuality\Category::getName
     */
    #[DataProvider('categoriesDataProvider')]
    public function testGetName(Category $category): void
    {
        self::assertNotEmpty($category->getName());
        self::assertIsString($category->getName());
    }
}
