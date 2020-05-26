<?php

namespace AirQuality;

/**
 * Class AirQualityDTO
 *
 * @package AirQualityCalculator
 */
class AirQualityDTO
{
    /**
     * @var null|float
     */
    public $index;

    /**
     * @var null|float
     */
    public $nowCast;

    /**
     * Value| Desc                           | Color
     * -----+--------------------------------+--------
     * null | No data                        | -
     * 0    | Good                           | #00e400
     * 1    | Moderate                       | #ff0
     * 2    | Unhealthy for Sensitive Groups | #ff7e00
     * 3    | Unhealthy                      | #f00
     * 4    | Very Unhealthy                 | #99004c
     * 5    | Hazardous                      | #7e0023
     * 6    | Very Hazardous                 | #7e0023
     *
     * @var null|int
     */
    public $category;
}
