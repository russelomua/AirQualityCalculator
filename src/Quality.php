<?php
declare(strict_types=1);

namespace AirQuality;

readonly class Quality implements \JsonSerializable
{
    public function __construct(
        public ?float $nowCast,
        public Index $index,
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'nowCast' => $this->nowCast,
            ...$this->index->jsonSerialize(),
        ];
    }
}
