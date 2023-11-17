<?php

declare(strict_types=1);

namespace Drew\MonologInfluxDB\ValueObject;

final class Values implements \Stringable
{
    /**
     * @param array<string, string|float|int> $values
     */
    private function __construct(
        private readonly array $values,
    ) {}

    public static function create(\Monolog\LogRecord $record): self
    {
        return new self(
            array_filter(
                array: $record->context,
                callback: fn (string $key) => str_starts_with($key, '_') === false,
                mode: ARRAY_FILTER_USE_KEY,
            )
        );
    }

    public function __toString(): string
    {
        $ret = [];

        foreach ($this->values as $k => $v) {
            if (is_string($v) || is_float($v)) {
                $ret[] = sprintf('%s="%s"', $k, $v);
            } elseif (is_int($v)) {
                $ret[] = sprintf('%s=%si', $k, $v);
            }
        }

        return implode(',', $ret);
    }
}
