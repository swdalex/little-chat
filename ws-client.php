<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use Ratchet\Client\Connector;
use React\EventLoop\Factory;

$loop = Factory::create();
$connector = new Connector($loop);

$connector('ws://localhost:8081')->then(
    function ($conn) {
        $conn->on('message', function ($msg) {
            echo "Received: $msg\n";
        });

        // Send a message from CLI
        $conn->send("Hello from client!");

        // Close after 5 seconds
        $conn->on('close', function () {
            echo "Connection closed\n";
        });

        $loop->addTimer(5, function () use ($conn) {
            $conn->close();
        });
    },
    function ($e) {
        echo "Could not connect: {$e->getMessage()}\n";
    }
);

$loop->run();
