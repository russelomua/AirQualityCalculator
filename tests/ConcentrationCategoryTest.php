<?php

namespace Tests;

use AirQuality\ConcentrationCategory;
use PHPUnit\Framework\TestCase;

class ConcentrationCategoryTest extends TestCase
{

    /**
     * @covers \AirQuality\ConcentrationCategory
     */
    public function test(): void
    {
        $concentration = new ConcentrationCategory(0, 1);
        $this->assertSame(0, $concentration->low);
        $this->assertSame(1, $concentration->high);
    }

    /**
     * @covers \AirQuality\ConcentrationCategory
     */
    public function testInRange(): void
    {
        $concentration = new ConcentrationCategory(0, 1);

        $this->assertTrue($concentration->inRange(0));
        $this->assertTrue($concentration->inRange(.5));
        $this->assertTrue($concentration->inRange(1));

        $this->assertFalse($concentration->inRange(-1));
        $this->assertFalse($concentration->inRange(2));
    }
}
