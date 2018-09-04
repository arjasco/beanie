<?php

namespace Arjasco\Beanie;

use Arjasco\Beanie\Exceptions\SocketException;

class Socket
{
    /**
     * Address to connect to.
     *
     * @var string
     */
    protected $address;
    
    /**
     * Port to connect to.
     *
     * @var int
     */
    protected $port;

    /**
     * Create a persistent connection.
     *
     * @var bool
     */
    protected $persistent;

    /**
     * Socket timeout.
     *
     * @var int
     */
    protected $timeout;

    /**
     * Socket resource
     *
     * @var resource
     */
    protected $socket;

    /**
     * Create a new socket.
     *
     * @param string $address
     * @param int $port
     * @param bool $persistent
     * @param int $timeout
     */
    public function __construct($address, $port, $persistent = false, $timeout = 0)
    {
        $this->address = $address;
        $this->port = $port;
        $this->persistent = $persistent;
        $this->timeout = $timeout;
        
        $this->connect();
    }

    /**
     * Establish a connection to the socket.
     *
     * @return void
     * @throws \Arjasco\Beanie\Exceptions\SocketException
     */
    protected function connect()
    {
        if ($this->persistent) {
            $this->socket = pfsockopen($this->address, $this->port, $errno, $errstr);
        } else {
            $this->socket = fsockopen($this->address, $this->port, $errno, $errstr);
        }

        if ($this->timeout) {
            stream_set_timeout($this->socket, $this->timeout);
        }

        if (! $this->socket) {
            throw new SocketException(
                sprintf("Connect failed. [%s] %s", $errno, $errstr)
            );
        }
    }

    /**
     * Read a specified length of data from the socket.
     *
     * @param integer $length
     * @return string
     */
    public function read($length = 1024)
    {
        return fread($this->socket, $length);
    }

    /**
     * Read to EOL.
     *
     * @return string
     */
    public function readLine()
    {
        return fgets($this->socket);
    }

    /**
     * Write data to the socket.
     *
     * @param string $content
     * @return void
     */
    public function write($content)
    {
        if (fwrite($this->socket, $content) === false) {
            throw new SocketException(
                'Write failed. Reason: ' . socket_strerror(socket_last_error())
            );
        }
    }

    /**
     * Disconnect from the socket.
     *
     * @return void
     */
    public function disconnect()
    {
        fclose($this->socket);
    }
}