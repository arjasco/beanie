<?php

namespace Arjasco\Beanie;

use ArrayIterator;
use Arjasco\Beanie\Strategies\RoundRobin;

class Pool implements ConnectionInterface, IteratorAggregate
{
    /**
     * Pool of connections.
     *
     * @var array
     */
    protected $connections = [];

    /**
     * Pool strategy.
     *
     * @var Arjasco\Beanie\Pool\Strategy\StrategyInterface
     */
    protected $strategy;

    /**
     * Create a new pool.
     *
     * @param array $connections
     * @param Arjasco\Beanie\Strategies\StrategyInterface $strategy
     */
    public function __construct($connections, StrategyInterface $strategy = null)
    {
        foreach ($connections as $connection) {
            $this->addConnection($connection);
        }

        $this->setStrategy($strategy);
    }

    /**
     * Add a connection to the pool
     *
     * @param Connection $connection
     * @return void
     */
    public function addConnection(Connection $connection)
    {
        $this->connections[$connection->getName()] = $connection;
    }

    /**
     * Send a command to a connection.
     *
     * @param \Arjasco\Beanie\Commands\Command $command
     * @return \Arjasco\Beanie\Reply
     */
    public function send(Command $command)
    {
        $connection = $this->strategy->getConnection($this->connections);

        return $connection->send($command);
    }

    /**
     * Send a command to specific connection.
     *
     * @param \Arjasco\Beanie\Commands\Command $command
     * @return \Arjasco\Beanie\Reply
     */
    public function sendTo(Command $command, $connectionName)
    {
        if (! isset($this->connections[$connectionName])) {
            throw new Exception('Connection not found.');
        }

        $connection = $this->connections[$connectionName];

        return $connection->send($command);
    }

    /**
     * Set pool balancing strategy.
     *
     * @param \Arjasco\Beanie\Strategies\StrategyInterface $strategy
     * @return void
     */
    public function setStrategy(StrategyInterface $strategy)
    {
        if (! is_null($strategy)) {
            $this->strategy = $strategy;
        } else {
            $this->strategy = new RoundRobin;
        }
    }

    /**
     * Get the underlying array for iteration.
     *
     * @return Traversable
     */
    public function getIterator()
    {
        return new ArrayIterator($this->connections);
    }
}