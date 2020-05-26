<?php

use AirQuality\Pollutants\GenericPollutant;
use PHPUnit\Framework\TestCase;

class GenericPollutantTest extends TestCase
{
    public static $pollutant;

    /**
     * @param $precisionValue
     * @param $breakpointsValue
     *
     * @return GenericPollutant
     * @throws ReflectionException
     */
    public function createPollutant($precisionValue, $breakpointsValue)
    {
        /** @var GenericPollutant $pollutant */
        $pollutant = $this->getMockBuilder(GenericPollutant::class)->getMockForAbstractClass();

        $reflection = new ReflectionClass(GenericPollutant::class);

        $breakpoints = $reflection->getProperty('breakpoints');
        $breakpoints->setAccessible(true);
        $breakpoints->setValue($pollutant, $breakpointsValue);

        $precision = $reflection->getProperty('precision');
        $precision->setAccessible(true);
        $precision->setValue($pollutant, $precisionValue);

        return $pollutant;
    }

    public function pollutantDataProvider()
    {
        return [
            [
                0,
                [
                    0 => [0, 1],
                    1 => [2, 3],
                    2 => [4, 5],
                    3 => [6, 7],
                ],
            ],
            [
                3,
                [
                    0 => [0, 0.001],
                    1 => [0.002, 0.003],
                    2 => [0.004, 0.005],
                    3 => [0.006, 0.007],
                ],
            ],
        ];
    }

    /**
     * @param $precision
     * @param $breakpoints
     *
     * @throws ReflectionException
     * @dataProvider pollutantDataProvider
     */
    public function testGetAirQualityCategory($precision, $breakpoints)
    {
        $pollutant = $this->createPollutant($precision, $breakpoints);

        foreach ($breakpoints as $expectedCategory => $breakpoint) {
            $category = $pollutant->getAirQualityCategory($breakpoint[0]);
            $this->assertSame($expectedCategory, $category);

            $category = $pollutant->getAirQualityCategory($breakpoint[1]);
            $this->assertSame($expectedCategory, $category);
        }
    }


    /**
     * @param $precision
     * @param $breakpoints
     *
     * @throws ReflectionException
     * @dataProvider pollutantDataProvider
     */
    public function testInvalidGetAirQualityCategory($precision, $breakpoints)
    {
        $this->expectException(UnexpectedValueException::class);

        $pollutant = $this->createPollutant($precision, $breakpoints);

        $pollutant->getAirQualityCategory(-10);
    }

    /**
     * @param $precision
     * @param $breakpoints
     *
     * @throws ReflectionException
     * @dataProvider pollutantDataProvider
     */
    public function testTruncateNowCast($precision, $breakpoints)
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

    /**
     * @param $precision
     * @param $breakpoints
     *
     * @throws ReflectionException
     * @dataProvider pollutantDataProvider
     */
    public function testGetLowBreakpoint($precision, $breakpoints)
    {
        $pollutant = $this->createPollutant($precision, $breakpoints);

        foreach ($breakpoints as $category => $breakpoint) {
            $lowBreakpoint = $pollutant->getLowBreakpoint($category);
            $this->assertSame($lowBreakpoint, $breakpoint[0]);
        }
    }

    /**
     * @param $precision
     * @param $breakpoints
     *
     * @throws ReflectionException
     * @dataProvider pollutantDataProvider
     */
    public function testInvalidGetLowBreakpoint($precision, $breakpoints)
    {
        $this->expectException(UnexpectedValueException::class);

        $pollutant = $this->createPollutant($precision, $breakpoints);

        $pollutant->getLowBreakpoint(null);
    }

    /**
     * @param $precision
     * @param $breakpoints
     *
     * @throws ReflectionException
     * @dataProvider pollutantDataProvider
     */
    public function testGetHighBreakpoint($precision, $breakpoints)
    {
        $pollutant = $this->createPollutant($precision, $breakpoints);

        foreach ($breakpoints as $category => $breakpoint) {
            $lowBreakpoint = $pollutant->getHighBreakpoint($category);
            $this->assertSame($lowBreakpoint, $breakpoint[1]);
        }
    }

    /**
     * @param $precision
     * @param $breakpoints
     *
     * @throws ReflectionException
     * @dataProvider pollutantDataProvider
     */
    public function testInvalidGetHighBreakpoint($precision, $breakpoints)
    {
        $this->expectException(UnexpectedValueException::class);

        $pollutant = $this->createPollutant($precision, $breakpoints);

        $pollutant->getHighBreakpoint(null);
    }
}
