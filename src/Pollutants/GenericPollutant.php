<?php
namespace AirQuality\Pollutants;

abstract class GenericPollutant implements PollutantInterface
{
    /** @var array */
    protected $breakpoints = [];

    /** @var int */
    protected $precision = 0;

    /**
     * @inheritDoc
     */
    public function getAirQualityCategory($nowCast)
    {
        $truncatedNowCast = $this->truncateNowCast($nowCast);

        foreach ($this->breakpoints as $category => $breakpoint) {
            if ($truncatedNowCast >= $breakpoint[0] && $truncatedNowCast <= $breakpoint[1]) {
                return $category;
            }
        }

        throw new \UnexpectedValueException('NowCast is out of range of breakpoints');
    }

    /**
     * @inheritDoc
     */
    public function truncateNowCast($nowCast)
    {
        $roundedNowCast = round($nowCast, $this->precision);

        // @codeCoverageIgnoreStart
        if ($roundedNowCast === false) {
            throw new \UnexpectedValueException('Failed to round value');
        }
        // @codeCoverageIgnoreEnd

        return $roundedNowCast;
    }

    /**
     * @inheritDoc
     */
    public function getLowBreakpoint($category)
    {
        if (!array_key_exists($category, $this->breakpoints)) {
            throw new \UnexpectedValueException('Unexpected pollutant category');
        }
        return $this->breakpoints[$category][0];
    }

    /**
     * @inheritDoc
     */
    public function getHighBreakpoint($category)
    {
        if (!array_key_exists($category, $this->breakpoints)) {
            throw new \UnexpectedValueException('Unexpected pollutant category');
        }
        return $this->breakpoints[$category][1];
    }
}
