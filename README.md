# Little Chat

A minimal real-time chat server using ReactPHP. 
Clients (e.g., a simple PHP CLI script or a browser WebSocket client) can connect, 
send messages, and receive messages in real time.

Key Features:

- Accept multiple WebSocket connections
- Broadcast messages to all connected clients
- Basic user identification (e.g., nickname system)
- Graceful shutdown handling