<?php

use AirQuality\AirQualityCalculator;
use AirQuality\AirQualityDTO;
use AirQuality\AirQualityIndex;
use AirQuality\Pollutants\PM25Pollutant;
use AirQuality\Pollutants\PollutantInterface;
use PHPUnit\Framework\TestCase;

class AirQualityCalculatorTest extends TestCase
{
    private $reducePrecision = .25;

    /** @var AirQualityCalculator */
    private static $calculator;

    /** @var PollutantInterface */
    private static $pollutant;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        static::$calculator = new AirQualityCalculator();
        static::$pollutant = new PM25Pollutant();
    }

    /**
     * @return array[]
     */
    public function preCalculatedNowCastDataProvider()
    {
        return [
            // Concentrations, NowCast, AQI, AirQualityIndex
            [[13, 16, 10, 21, 74, 64, 53, 82, 90, 75, 80, 50], 17.4, 62, AirQualityIndex::MODERATE],
        ];
    }

    public function preCalculatedNowCastDataWithEmpty()
    {
        $data = $this->preCalculatedNowCastDataProvider();
        $data[] = [[], null, null, null];
        return $data;
    }

    /**
     * Removing in loop one by one value of concentrations measurements and
     * calculated value must be in a 25% range of expected value of NowCast
     *
     * @param $concentrations
     * @param $expectedNowCast
     *
     * @dataProvider preCalculatedNowCastDataProvider
     * @covers       \AirQuality\AirQualityCalculator::calculateNowCast
     */
    public function testNowCastReduceConcentrationsValues($concentrations, $expectedNowCast)
    {
        $until = count($concentrations) - 3;

        for ($i = 0; $i < $until; $i++) {
            $nowCast = static::$calculator->calculateNowCast($concentrations);

            $diff = round($expectedNowCast / $nowCast, 2);

            $count = count($concentrations);
            $message = "Calculated NowCast \"$nowCast\" reduced to {$count} hours is out of 25% range";
            $this->assertEqualsWithDelta(1, $diff, $this->reducePrecision, $message);

            array_pop($concentrations);
        }
    }

    /**
     * Set in loop one of values of concentrations measurements to zero and
     * calculated value must be in a 25% range of expected value of NowCast
     *
     * @param $concentrations
     * @param $expectedNowCast
     *
     * @dataProvider preCalculatedNowCastDataProvider
     * @covers       \AirQuality\AirQualityCalculator::calculateNowCast()
     */
    public function testNowCastMissingConcentrationsValues($concentrations, $expectedNowCast)
    {
        for ($i = 0, $count = count($concentrations); $i < $count; $i++) {
            $missedConcentrations = $concentrations;
            $missedConcentrations[$i] = 0;

            $nowCast = static::$calculator->calculateNowCast($missedConcentrations);

            $diff = round($expectedNowCast / $nowCast, 2);
            $count = count($concentrations);
            $message = "Calculated NowCast \"$nowCast reduced to {$count} hours is out of 25% range";
            $this->assertEqualsWithDelta(1, $diff, $this->reducePrecision, $message);
        }
    }

    /**
     * @param $concentrations
     * @param $expected
     *
     * @dataProvider isConcentrationsValidDataProvider
     * @covers       \AirQuality\AirQualityCalculator::calculateNowCast
     * @covers       \AirQuality\AirQualityCalculator::isConcentrationsValid
     * @covers       \AirQuality\AirQualityCalculator::isAtLeastTwoOfTreeFirst
     */
    public function testIsConcentrationsValid($concentrations, $expected)
    {
        $nowCast = static::$calculator->calculateNowCast($concentrations);
        if ($expected) {
            $this->assertNotNull($nowCast);
        } else {
            $this->assertNull($nowCast);
        }
    }

    public function isConcentrationsValidDataProvider()
    {
        return [
            'null'  => [null, false],
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
     * @param $concentrations
     * @param $expectedNowCast
     * @param $expectedAqi
     *
     * @dataProvider preCalculatedNowCastDataWithEmpty
     * @covers       \AirQuality\AirQualityCalculator::calculateAQI
     * @covers       \AirQuality\AirQualityCalculator::getAirQualityIndex
     */
    public function testCalculateAQI($concentrations, $expectedNowCast, $expectedAqi)
    {
        $nowCast = static::$calculator->calculateNowCast($concentrations);
        $aqi = static::$calculator->calculateAQI($nowCast, static::$pollutant);

        $this->assertSame($aqi, $expectedAqi);
    }

    /**
     * @param $concentrations
     * @param $expectedNowCast
     * @param $expectedAqi
     *
     * @param $expectedCategory
     *
     * @dataProvider preCalculatedNowCastDataWithEmpty
     * @covers       \AirQuality\AirQualityCalculator::calculateAQI
     * @covers       \AirQuality\AirQualityCalculator::getAirQuality()
     */
    public function testGetAQI($concentrations, $expectedNowCast, $expectedAqi, $expectedCategory)
    {
        $airQualityDTO = static::$calculator->getAirQuality($concentrations, static::$pollutant);

        $this->assertInstanceOf(AirQualityDTO::class, $airQualityDTO);

        $this->assertSame($expectedNowCast, $airQualityDTO->nowCast);
        $this->assertSame($expectedAqi, $airQualityDTO->index);
        $this->assertSame($expectedCategory, $airQualityDTO->category);
    }
}
