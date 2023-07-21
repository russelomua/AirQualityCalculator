<?php

namespace AirQuality;

use AirQuality\Pollutants\PollutantInterface;

final class NowCast
{
    /** @psalm-suppress PropertyNotSetInConstructor it must be undefined */
    private ?float $nowCast;

    public function __construct(
        /** @var list<int|float|null> */
        private readonly array $tuple
    ) {
    }

    public function createCalculator(PollutantInterface $pollutant): Calculator
    {
        return new Calculator($this, new ConcentrationCategoryResolver($pollutant));
    }

    public function getValue(): ?float
    {
        return $this->nowCast ?? $this->nowCast = $this->calculate();
    }

    private function calculate(): ?float
    {
        if (!$this->isValid()) {
            return null;
        }

        $scaledRate = $this->getScaleRate();
        $weightFactor = (1 - $scaledRate) > 0.5 ? $scaledRate : 0.5;

        $nowCastUp = 0;
        $nowCastDown = 0;

        foreach ($this->tuple as $i => $concentration) {
            if ($concentration > 0) {
                $nowCastUp += ($weightFactor ** $i) * $concentration;
                $nowCastDown += $weightFactor ** $i;
            }
        }

        return round($nowCastUp / $nowCastDown, 1);
    }

    public function isValid(): bool
    {
        return count($this->tuple) >= 2 && $this->hasAtLeastTwoOfFirstThreeItems();
    }

    private function hasAtLeastTwoOfFirstThreeItems(): bool
    {
        $limit = min(count($this->tuple), 3);

        $count = 0;
        for ($i = 0; $i < $limit; $i++) {
            if (array_key_exists($i, $this->tuple)
                && is_numeric($this->tuple[$i])
                && $this->tuple[$i] > 0
            ) {
                $count++;
            }
        }

        return $count >= 2;
    }

    private function getScaleRate(): int|float
    {
        if (empty($this->tuple)) {
            return 0; // @codeCoverageIgnore
        }

        $minConcentration = min($this->tuple) ?: 0;
        $maxConcentration = max($this->tuple) ?: 0;

        return ($maxConcentration - $minConcentration) / $maxConcentration;
    }
}
