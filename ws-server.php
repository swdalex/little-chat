<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

class ChatServer implements MessageComponentInterface {
    private array $clients = [];

    public function onOpen(ConnectionInterface $conn) {
        $this->clients[$conn->resourceId] = $conn;
        echo "New connection: ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        echo "Message from ({$from->resourceId}): $msg\n";
        foreach ($this->clients as $client) {
            if ($client !== $from) {
                $client->send("User {$from->resourceId}: $msg");
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        unset($this->clients[$conn->resourceId]);
        echo "Connection {$conn->resourceId} closed\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
        $conn->close();
    }
}

// Start the server
$server = IoServer::factory(
    new HttpServer(
        new WsServer(new ChatServer())
    ),
    8081
);

echo "WebSocket server started on port 8081\n";
$server->run();
