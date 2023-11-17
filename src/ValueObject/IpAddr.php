<?php

declare(strict_types=1);

namespace Drew\MonologInfluxDB\ValueObject;

final class IpAddr
{
    private function __construct(
        public readonly string $value,
    ) {}

    public static function create(string $hostOrIp): self
    {
        return match (true) {
            filter_var($hostOrIp, FILTER_VALIDATE_IP) !== false => new self($hostOrIp),
            filter_var($hostOrIp, FILTER_VALIDATE_DOMAIN) !== false => self::createFromHost($hostOrIp),
            default => throw new \InvalidArgumentException('Wrong host or IP `' . $hostOrIp . '`'),
        };
    }

    private static function createFromHost(string $host): self
    {
        $ip = gethostbyname($host);

        if ($ip === $host) {
            throw new \InvalidArgumentException('Cannot resolve host `' . $host . '`');
        }

        return new self($ip);
    }
}
