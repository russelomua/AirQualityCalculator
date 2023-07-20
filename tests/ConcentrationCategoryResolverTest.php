<?php

namespace Tests;

use AirQuality\Category;
use AirQuality\ConcentrationCategory;
use AirQuality\ConcentrationCategoryResolver;
use AirQuality\NowCast;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ConcentrationCategoryResolverTest extends TestCase
{
    use PollutantTrait;

    /**
     * @covers \AirQuality\ConcentrationCategoryResolver::get
     * @covers \AirQuality\ConcentrationCategory
     */
    public function testGetCategoryOutOfRange(): void
    {
        $pollutant = $this->createPollutant(0, [new ConcentrationCategory(0, 1)]);
        $categoryResolver = new ConcentrationCategoryResolver($pollutant);

        self::assertNull($categoryResolver->get(1000));
    }

    /**
     * @covers \AirQuality\ConcentrationCategoryResolver::get
     * @covers \AirQuality\ConcentrationCategory
     */
    public function testGetCategoryNull(): void
    {
        $pollutant = $this->createPollutant(0, [new ConcentrationCategory(0, 1)]);
        $categoryResolver = new ConcentrationCategoryResolver($pollutant);

        self::assertNull($categoryResolver->get(null));
    }


    /**
     * @covers \AirQuality\ConcentrationCategoryResolver::get
     * @covers \AirQuality\ConcentrationCategory
     */
    #[DataProvider('concentrationHighLowDataProvider')]
    public function testSameCategory(string $highLow): void
    {
        $pollutant = $this->createPollutant(0, [
            new ConcentrationCategory(0, 1),
            new ConcentrationCategory(2, 3),
            new ConcentrationCategory(4, 5),
            new ConcentrationCategory(6, 7),
            new ConcentrationCategory(8, 9),
            new ConcentrationCategory(10, 11),
            new ConcentrationCategory(12, 13),
        ]);

        $categoryResolver = new ConcentrationCategoryResolver($pollutant);

        foreach (Category::cases() as $expectedCategory) {
            $concentration = $pollutant->getConcentration($expectedCategory);
            $concentrationCategory = $categoryResolver->get((float) $concentration->{$highLow});

            $this->assertSame($expectedCategory, $concentrationCategory->getCategory());
        }
    }

    public static function concentrationHighLowDataProvider(): iterable
    {
        yield 'low' => ['low'];
        yield 'high' => ['high'];
    }
}
