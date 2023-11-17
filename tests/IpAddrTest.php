<?php

declare(strict_types=1);

namespace tests;

use Drew\MonologInfluxDB\ValueObject\IpAddr;
use Monolog\Test\TestCase;

class IpAddrTest extends TestCase
{
    public function test1(): void
    {
        $ip = '127.0.0.1';

        $ipAddr = IpAddr::create($ip);

        $this->assertEquals($ip, $ipAddr->value);
    }

    public function test2(): void
    {
        $host = 'example.com';
        $ip = '93.184.216.34';

        $ipAddr = IpAddr::create($host);

        $this->assertEquals($ip, $ipAddr->value);
    }

    public function test3(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('%^Cannot resolve host%');

        IpAddr::create('error');
    }
}
