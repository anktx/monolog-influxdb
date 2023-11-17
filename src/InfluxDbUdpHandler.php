<?php

declare(strict_types=1);

namespace Drew\MonologInfluxDB;

use Drew\MonologInfluxDB\ValueObject\IpAddr;
use Drew\MonologInfluxDB\ValueObject\Port;
use Drew\MonologInfluxDB\ValueObject\Tags;
use Drew\MonologInfluxDB\ValueObject\Values;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Handler\MissingExtensionException;
use Monolog\Handler\SyslogUdp\UdpSocket;
use Monolog\Level;
use Monolog\LogRecord;

final class InfluxDbUdpHandler extends AbstractProcessingHandler
{
    private readonly UdpSocket $socket;

    public function __construct(
        string $host,
        int $port,
        private readonly string $measurement,
        int|string|Level $level = Level::Debug,
        bool $bubble = true,
    ) {
        if (extension_loaded('sockets') === false) {
            throw new MissingExtensionException('The sockets extension is required to use the InfluxDbUdpHandler');
        }

        parent::__construct($level, $bubble);

        $this->socket = new UdpSocket(
            ip: IpAddr::create($host)->value,
            port: Port::create($port)->value,
        );
    }

    protected function write(LogRecord $record): void
    {
        $tags = Tags::create($record);
        $values = Values::create($record);
        $timestampUs = (int) $record->datetime->format('Uu');

        $line = sprintf('%s,%s %s %d000', $this->measurement, $tags, $values, $timestampUs);

        $this->socket->write($line);
    }

    public function close(): void
    {
        $this->socket->close();
    }
}
