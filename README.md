# monolog-influxdb

InfluxDB Handler for Monolog, which allows to send metrics to InfluxDB.

## Install


``` bash
$ composer require drew/monolog-influxdb
```

## Usage

``` php
<?php

$influxUdpHandler = new \Drew\MonologInfluxDB\InfluxDbUdpHandler(
    host: '127.0.0.1',
    port: 8089,
    measurement: 'test',
    level: \Monolog\Logger::DEBUG,
);

// Create logger
$logger = new \Monolog\Logger('channel');
$logger->pushHandler($influxUdpHandler);

// example on how to send metrics
$logger->info(
    '[anything]', // not used
    [
        '_room' => 'kitchen', // prefix "_" means tag
        'temp' => 24.3,
        'humid' => 28,
        'light' => 'on',
    ],
);
```
