<?php


use AirQuality\Pollutants\PollutantInterface;
use AirQuality\Pollutants\PollutantsFactory;
use PHPUnit\Framework\TestCase;

class PollutantsFactoryTest extends TestCase
{
    /** @var PollutantsFactory */
    public static $factory;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        static::$factory = new PollutantsFactory();
    }

    public function pollutantsDataProvider()
    {
        return [
            [PollutantsFactory::PM25, true],
            [PollutantsFactory::PM10, true],
            [PollutantsFactory::CO, true],
            [PollutantsFactory::SO2, true],
            [PollutantsFactory::NO2, true],
            [PollutantsFactory::OZONE_8H, true],
            [PollutantsFactory::OZONE_1H, true],
            ['test', false],
            [null, false],
            [false, false],
            [new stdClass(), false],
        ];
    }

    /**
     * @param string $pollutantName
     * @param $isValid
     * @dataProvider pollutantsDataProvider
     */
    public function testCreate($pollutantName, $isValid)
    {
        if (!$isValid) {
            $this->expectException(\UnexpectedValueException::class);
        }

        $pollutantInstance = static::$factory->create($pollutantName);

        $this->assertInstanceOf(PollutantInterface::class, $pollutantInstance);
    }
}
