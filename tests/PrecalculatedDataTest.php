<?php

namespace Tests;

use AirQuality\Calculator;
use AirQuality\Category;
use AirQuality\NowCast;
use AirQuality\Pollutants\PM25Pollutant;
use AirQuality\Pollutants\PollutantInterface;
use AirQuality\Quality;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class PrecalculatedDataTest extends TestCase
{
    private float $reducePrecision = .25;
    private static PollutantInterface $pollutant;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        static::$pollutant = new PM25Pollutant();
    }

    /**
     * @return array<int, array<list<int>, null|float, null|int, null|int>>
     */
    public static function preCalculatedNowCastDataProvider(): array
    {
        return [
            // Concentrations, NowCast, AQI, AirQualityIndex
            [[13, 16, 10, 21, 74, 64, 53, 82, 90, 75, 80, 50], 17.4, 62, Category::MODERATE],
        ];
    }

    /**
     * @return array<int, array<list<int>, null|float, null|int, null|int>>
     */
    public static function preCalculatedNowCastDataWithEmpty(): array
    {
        $data = PrecalculatedDataTest::preCalculatedNowCastDataProvider();
        $data[] = [[], null, null, null];

        return $data;
    }

    /**
     * Removing in loop one by one value of concentrations measurements and
     * calculated value must be in a 25% range of expected value of NowCast
     *
     * @param list<int|null> $concentrations
     * @param null|float     $expectedNowCast
     *
     * @covers \AirQuality\NowCast::getValue
     * @covers \AirQuality\NowCast::calculate
     */
    #[DataProvider('preCalculatedNowCastDataProvider')]
    public function testNowCastReduceConcentrationsValues(array $concentrations, ?float $expectedNowCast): void
    {
        $until = count($concentrations) - 3;

        for ($i = 0; $i < $until; $i++) {
            $nowCastValue = (new NowCast($concentrations))->getValue();

            $diff = round($expectedNowCast / $nowCastValue, 2);

            $message = sprintf('Calculated NowCast "%s" reduced to %s hours is out of 25%% range', $nowCastValue, count($concentrations));
            $this->assertEqualsWithDelta(1, $diff, $this->reducePrecision, $message);

            array_pop($concentrations);
        }
    }

    /**
     * Set in loop one of values of concentrations measurements to zero and
     * calculated value must be in a 25% range of expected value of NowCast
     *
     * @param list<int|null> $concentrations
     * @param null|float     $expectedNowCast
     *
     * @covers \AirQuality\NowCast::getValue
     * @covers \AirQuality\NowCast::calculate
     */
    #[DataProvider('preCalculatedNowCastDataProvider')]
    public function testNowCastMissingConcentrationsValues(array $concentrations, ?float $expectedNowCast): void
    {
        for ($i = 0; $i < count($concentrations); $i++) {
            $missedConcentrations = $concentrations;
            $missedConcentrations[$i] = 0;

            $nowCastValue = (new NowCast($missedConcentrations))->getValue();

            $diff = round($expectedNowCast / $nowCastValue, 2);
            $message = sprintf('Calculated NowCast "%s reduced to %s hours is out of 25%% range', $nowCastValue, count($concentrations));
            $this->assertEqualsWithDelta(1, $diff, $this->reducePrecision, $message);
        }
    }

    /**
     * @param list<int|null> $concentrations
     * @param bool           $expected
     *
     * @covers \AirQuality\NowCast::isValid
     * @covers \AirQuality\NowCast::isConcentrationsValid
     * @covers \AirQuality\NowCast::isAtLeastTwoOfTreeFirst
     */
    #[DataProvider('isConcentrationsValidDataProvider')]
    public function testIsConcentrationsValid(array $concentrations, bool $expected): void
    {
        self::assertSame($expected, (new NowCast($concentrations))->isValid());
    }

    /**
     * @return array<int, array<null|list<int|null>, bool>>
     */
    public static function isConcentrationsValidDataProvider(): array
    {
        return [
            'empty' => [[], false],
            'one'   => [[6], false],
            'two'   => [[6, 6], true],
            'I X X' => [[6, 0, 0], false],
            'X I X' => [[0, 6, 0], false],
            'X X I' => [[0, 0, 6], false],
            'I I I' => [[6, 6, 6], true],
            'I I X' => [[6, 6, 0], true],
            'I X I' => [[6, 0, 6], true],
            'X I I' => [[0, 6, 6], true],
        ];
    }

    /**
     * @param list<int|null> $concentrations
     * @param float|null     $expectedNowCast
     * @param int|null       $expectedAqi
     *
     * @covers       \AirQuality\Calculator::getIndex
     * @covers       \AirQuality\Calculator::getQuality
     */
    #[DataProvider('preCalculatedNowCastDataWithEmpty')]
    public function testCalculateAQI(array $concentrations, ?float $expectedNowCast, ?int $expectedAqi): void
    {
        $nowCast = new NowCast($concentrations);
        $calculator = $nowCast->createCalculator(static::$pollutant);
        $index = $calculator->getIndex();

        $this->assertSame($index->value, $expectedAqi);
    }


    /**
     * @param list<int|null> $concentrations
     *
     * @covers       \AirQuality\Calculator::getIndex
     * @covers       \AirQuality\Calculator::getQuality
     * @covers       \AirQuality\Quality
     */
    #[DataProvider('preCalculatedNowCastDataWithEmpty')]
    public function testGetAQI(array $concentrations, ?float $expectedNowCast, ?int $expectedAqi, ?Category $expectedCategory): void
    {
        $nowCast = new NowCast($concentrations);
        $calculator = $nowCast->createCalculator(static::$pollutant);

        $dto = $calculator->getQuality();
        $this->assertInstanceOf(Quality::class, $dto);

        $this->assertSame($expectedNowCast, $dto->nowCast);
        $this->assertSame($expectedAqi, $dto->index->value);
        $this->assertSame($expectedCategory, $dto->index->category);
    }
}
