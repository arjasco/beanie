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
     * Connection address.
     *
     * @var string
     */
    protected $address;

    /**
     * Connection port.
     *
     * @var int
     */
    protected $port;

    /**
     * Connection name.
     *
     * @var string
     */
    protected $name;

    /**
     * Create a new connection.
     *
     * @param string $address
     * @param int $port
     * @param string $name
     */
    public function __construct($address, $port, $name = null)
    {
        $this->address = $address;
        $this->port = $port;

        if (! is_null($name)) {
            $this->name = $name;
        } else {
            $this->name = $address . ':' . $port; 
        }

        $this->socket = new Socket($address, $port);
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

            // Read control characters to complete the command.
            $data = $this->socket->read(self::CRLF_LENGTH);

            // Add the additional data to the reply.
            $reply->setData($data);
        }

        return $reply;
    }

    /**
     * Get connection name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}