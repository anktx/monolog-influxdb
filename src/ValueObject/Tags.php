<?php

declare(strict_types=1);

namespace Drew\MonologInfluxDB\ValueObject;

final class Tags implements \Stringable
{
    /**
     * @param array<string, string> $values
     */
    private function __construct(
        private readonly array $values,
    ) {}

    public static function create(\Monolog\LogRecord $record): self
    {
        return new self(
            array_merge(
                array_filter(
                    array: $record->context,
                    callback: fn (string $key) => str_starts_with($key, '_'),
                    mode: ARRAY_FILTER_USE_KEY,
                ),
                [
                    'level' => $record->level->name,
                    'channel' => $record->channel,
                ],
            ),
        );
    }

    public function __toString(): string
    {
        $ret = [];

        foreach ($this->values as $k => $v) {
            $ret[] = sprintf('%s=%s', preg_replace('%^_%', '', $k), $v);
        }

        return implode(',', $ret);
    }
}
