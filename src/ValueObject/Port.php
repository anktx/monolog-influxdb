<?php

declare(strict_types=1);

namespace Drew\MonologInfluxDB\ValueObject;

final class Port
{
    private function __construct(
        public readonly int $value,
    ) {
        if ($this->value <= 0 || $this->value > 65535) {
            throw new \InvalidArgumentException('Wrong port number `' . $this->value . '`');
        }
    }

    public static function create(int $value): self
    {
        return new self($value);
    }
}
