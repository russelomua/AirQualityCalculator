<?php

namespace Tests;

use AirQuality\Category;
use AirQuality\ConcentrationCategory;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class PollutantInterfaceTest extends TestCase
{
    use PollutantTrait;

    public static function pollutantDataProvider(): iterable
    {
        yield 'precision zero' => [
            'precision'   => 0,
            'breakpoints' => [
                new ConcentrationCategory(0, 1),
                new ConcentrationCategory(2, 3),
                new ConcentrationCategory(4, 5),
                new ConcentrationCategory(6, 7),
                new ConcentrationCategory(8, 9),
                new ConcentrationCategory(10, 11),
                new ConcentrationCategory(12, 13),
            ],
        ];

        yield 'precision three' => [
            'precision'   => 3,
            'breakpoints' => [
                new ConcentrationCategory(0, 0.001),
                new ConcentrationCategory(0.002, 0.003),
                new ConcentrationCategory(0.004, 0.005),
                new ConcentrationCategory(0.006, 0.007),
                new ConcentrationCategory(0.008, 0.009),
                new ConcentrationCategory(0.010, 0.011),
                new ConcentrationCategory(0.012, 0.013),
            ],
        ];
    }

    #[DataProvider('pollutantDataProvider')]
    public function testTruncateNowCast(int $precision, array $breakpoints): void
    {
        $pollutant = $this->createPollutant($precision, $breakpoints);

        $pollutantDelta = 1 ** (-$precision);

        $nowCasts = [
            0.12345678912345678,
            100,
            999.999999999,
            783.444444449,
        ];

        foreach ($nowCasts as $nowCast) {
            $truncateNowCast = $pollutant->truncateNowCast($nowCast);
            $this->assertEqualsWithDelta($nowCast, $truncateNowCast, $pollutantDelta);
        }
    }

    #[DataProvider('pollutantDataProvider')]
    public function testGetBreakpoint(int $precision, array $breakpoints): void
    {
        $pollutant = $this->createPollutant($precision, $breakpoints);

        foreach (Category::cases() as $index => $expectedCategory) {
            $concentration = $pollutant->getConcentration($expectedCategory);
            $this->assertSame($breakpoints[$index], $concentration);
        }
    }
}
