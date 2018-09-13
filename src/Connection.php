<?php

namespace Arjasco\Beanie;

use Arjasco\Beanie\Commands\Command;
use Arjasco\Beanie\Reply;

class Connection implements ConnectionInterface
{
    /**
     * Control characters.
     *
     * @var string
     */
    const CRLF = "\r\n";

    /**
     * Control characters length.
     *
     * @var int
     */
    const CRLF_LENGTH = 2;

    /**
     * Connection socket.
     *
     * @var \Arjasaco\Beanie\Socket
     */
    protected $socket;

    /**
     * Create a new connection.
     * @param Socket $socket
     */
    public function __construct(Socket $socket)
    {
        $this->socket = $socket;
    }

    /**
     * Send a command to the server.
     *
     * @param \Arjasco\Beanie\Commands\Command $command
     * @return \Arjasco\Beanie\Reply
     */
    public function send(Command $command)
    {
        // Being the initial write to the socket.
        $this->socket->write($command->getLine() . self::CRLF);

        if ($command->hasAdditionalData()) {
            // Write the additional data to the socket.
            $this->socket->write($command->getData() . self::CRLF);
        }

        // Fetch the reply.
        $reply = new Reply($this->socket->readLine());

        if ($reply->hasAdditionalData()) {
            // Read the additional data from the socket.
            $data = $this->socket->read($reply->getBytes());

            // Add the additional data to the reply.
            $reply->setData($data);

            // Read control characters to complete the command.
            $this->socket->read(self::CRLF_LENGTH);
        }

        return $reply;
    }
}
