<?php
declare(strict_types=1);

namespace AirQuality;

final class Index implements \JsonSerializable
{
    public function __construct(
        public readonly ?int $value = null,
        public readonly ?Category $category = null,
    ) {
    }

    /**
     * @return array{index: int|null, category: int|null, categoryName: string|null}
     */
    public function jsonSerialize(): array
    {
        return [
            'index'        => $this->value,
            'category'     => $this->category?->value,
            'categoryName' => $this->category?->getName(),
        ];
    }
}
