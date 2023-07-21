<?php
declare(strict_types=1);

namespace AirQuality;

class Quality implements \JsonSerializable
{
    public function __construct(
        public readonly ?float $nowCast,
        public readonly Index $index,
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
