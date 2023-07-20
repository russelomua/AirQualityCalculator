<?php

namespace Tests;

use AirQuality\Category;
use AirQuality\ConcentrationCategory;
use AirQuality\Pollutants\COPollutant;
use AirQuality\Pollutants\NO2Pollutant;
use AirQuality\Pollutants\Ozone1hPollutant;
use AirQuality\Pollutants\Ozone8hPollutant;
use AirQuality\Pollutants\PM10Pollutant;
use AirQuality\Pollutants\PM25Pollutant;
use AirQuality\Pollutants\PollutantInterface;
use AirQuality\Pollutants\SO2Pollutant;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class PollutantsTest extends TestCase
{
    public static function truncateDataProvider(): iterable
    {
        yield 'CO' => [
            'pollutant'     => new COPollutant(),
            'nowCast'       => 1.234,
            'expectNowCast' => 1.2,
        ];

        yield 'NO2' => [
            'pollutant'     => new NO2Pollutant(),
            'nowCast'       => 1.234,
            'expectNowCast' => 1,
        ];

        yield 'Ozone1h' => [
            'pollutant'     => new Ozone1hPollutant(),
            'nowCast'       => 1.234567,
            'expectNowCast' => 1.235,
        ];

        yield 'Ozone8h' => [
            'pollutant'     => new Ozone8hPollutant(),
            'nowCast'       => 1.234567,
            'expectNowCast' => 1.235,
        ];

        yield 'PM10' => [
            'pollutant'     => new PM10Pollutant(),
            'nowCast'       => 1.234567,
            'expectNowCast' => 1,
        ];

        yield 'PM2.5' => [
            'pollutant'     => new PM25Pollutant(),
            'nowCast'       => 1.234567,
            'expectNowCast' => 1.2,
        ];

        yield 'SO2' => [
            'pollutant'     => new SO2Pollutant(),
            'nowCast'       => 1.234567,
            'expectNowCast' => 1,
        ];
    }

    /**
     * @covers \AirQuality\Pollutants
     */
    #[DataProvider('truncateDataProvider')]
    public function testTruncate(PollutantInterface $pollutant, float|int $nowCast, float|int $expectNowCast): void
    {
        $this->assertSame($expectNowCast, $pollutant->truncateNowCast($nowCast));
    }

    public static function categoriesDataProvider(): iterable
    {
        yield 'CO' => [new COPollutant()];
        yield 'NO2' => [new NO2Pollutant()];
        yield 'PM10' => [new PM10Pollutant()];
        yield 'PM2.5' => [new PM25Pollutant()];
        yield 'SO2' => [new SO2Pollutant()];
    }

    /**
     * @covers \AirQuality\Pollutants
     */
    #[DataProvider('categoriesDataProvider')]
    public function testCategories(PollutantInterface $pollutant): void
    {
        foreach (Category::cases() as $category) {
            $concentration = $pollutant->getConcentration($category);

            self::assertInstanceOf(ConcentrationCategory::class, $concentration);
            self::assertIsNumeric(0, $concentration->low);
            self::assertIsNumeric(0, $concentration->high);
        }
    }

    public function testCategoryOzone1h(): void
    {
        $pollutant = new Ozone1hPollutant();
        foreach (Category::cases() as $category) {
            if ($concentration = $pollutant->getConcentration($category)) {
                self::assertInstanceOf(ConcentrationCategory::class, $concentration);
                self::assertIsNumeric($concentration->low);
                self::assertIsNumeric($concentration->high);
            } else {
                // Ozone1h is not supported in this categories
                self::assertContains($category, [Category::GOOD, Category::MODERATE]);
            }
        }
    }

    public function testCategoryOzone8h(): void
    {
        $pollutant = new Ozone8hPollutant();

        foreach (Category::cases() as $category) {
            if ($concentration = $pollutant->getConcentration($category)) {
                self::assertInstanceOf(ConcentrationCategory::class, $concentration);
                self::assertIsNumeric($concentration->low);
                self::assertIsNumeric($concentration->high);
            } else {
                // Ozone8h is not supported in this categories
                self::assertContains($category, [Category::HAZARDOUS, Category::VERY_HAZARDOUS]);
            }
        }
    }
}
