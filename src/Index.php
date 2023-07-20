<?php
declare(strict_types=1);

namespace AirQuality;

final readonly class Index implements \JsonSerializable
{
    public function __construct(
        public ?int $value = null,
        public ?Category $category = null,
    ) {
    }

    /**
     * @return array{index: int|null, category: string|null, categoryName: string|null}
     */
    public function jsonSerialize(): array
    {
        return [
            'index'        => $this->value,
            'category'     => $this->category?->value,
            'categoryName' => null === $this->category ? null : strtolower($this->category->name),
        ];
    }
}
