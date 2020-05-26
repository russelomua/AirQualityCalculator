<?php

use AirQuality\AirQualityIndex;
use PHPUnit\Framework\TestCase;

class AirQualityIndexTest extends TestCase
{
    /** @var AirQualityIndex */
    static public $qualityIndex;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        static::$qualityIndex = new AirQualityIndex();
    }

    public function airQualityDataProvider()
    {
        return [
            [AirQualityIndex::GOOD, true],
            [AirQualityIndex::MODERATE, true],
            [AirQualityIndex::UNHEALTHY_SENSITIVE, true],
            [AirQualityIndex::UNHEALTHY, true],
            [AirQualityIndex::VERY_UNHEALTHY, true],
            [AirQualityIndex::HAZARDOUS, true],
            [AirQualityIndex::VERY_HAZARDOUS, true],
            ['test', false],
            [7243823435, false],
            [null, false],
        ];
    }

    /**
     * @param $category
     * @param $expectInt
     *
     * @dataProvider airQualityDataProvider
     */
    public function testGetLowIndex($category, $expectInt)
    {
        if (!$expectInt) {
            $this->expectException(\UnexpectedValueException::class);
        }
        $index = static::$qualityIndex->getLowIndex($category);
        $this->assertIsInt($index);
    }

    /**
     * @param $category
     * @param $expectInt
     *
     * @dataProvider airQualityDataProvider
     */
    public function testGetHighIndex($category, $expectInt)
    {
        if (!$expectInt) {
            $this->expectException(\UnexpectedValueException::class);
        }
        $index = static::$qualityIndex->getHighIndex($category);
        $this->assertIsInt($index);
    }
}
