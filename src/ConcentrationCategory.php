<?php
declare(strict_types=1);

namespace AirQuality;

final class ConcentrationCategory
{
    private ?Category $category = null;

    public function __construct(
        public readonly int|float $low,
        public readonly int|float $high,
    ) {
    }

    public function inRange(int|float $truncatedNowCast): bool
    {
        return $truncatedNowCast >= $this->low && $truncatedNowCast <= $this->high;
    }

    public function withCategory(Category $category): self
    {
        $clone = clone $this;
        $clone->category = $category;

        return $clone;
    }

    public function getCategory(): Category
    {
        return $this->category ?: throw new \LogicException('Category is not set');
    }
}
