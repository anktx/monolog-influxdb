<?php

declare(strict_types=1);

namespace tests;

use Drew\MonologInfluxDB\InfluxDbUdpHandler;
use Drew\MonologInfluxDB\ValueObject\Tags;
use Drew\MonologInfluxDB\ValueObject\Values;
use Monolog\Level;
use Monolog\Logger;
use Monolog\LogRecord;
use Monolog\Test\TestCase;

class HandlerTest extends TestCase
{
    private LogRecord $record;

    public function test1(): void
    {
        $tags = Tags::create($this->record);
        $values = Values::create($this->record);

        $this->assertEquals('room=kitchen,level=Debug,channel=channel', (string) $tags);
        $this->assertEquals('temp="24.3",humid=28i,light="on"', (string) $values);
    }

    public function test2(): void
    {
        $this->expectNotToPerformAssertions();

        $logger = new Logger('app');

        $logger->pushHandler(
            new InfluxDbUdpHandler(
                host: '127.0.0.1',
                port: 8089,
                measurement: 'test',
            )
        );

        $logger->info(
            message: 'test udp',
            context: $this->record->context,
        );
    }

    protected function setUp(): void
    {
        $this->record = new LogRecord(
            datetime: new \DateTimeImmutable(),
            channel: 'channel',
            level: Level::Debug,
            message: 'test udp channel',
            context: ['_room' => 'kitchen', 'temp' => 24.3, 'humid' => 28, 'light' => 'on'],
        );
    }
}
