<?php

declare(strict_types=1);

namespace tests;

use Drew\MonologInfluxDB\ValueObject\Port;
use Monolog\Test\TestCase;

class TestPort extends TestCase
{
    public function test1(): void
    {
        $p = 8089;

        $port = Port::create($p);

        $this->assertEquals($p, $port->value);
        $this->assertIsInt($port->value);
    }

    public function test2(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Port::create(-1);
    }
}
