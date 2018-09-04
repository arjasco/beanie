<?php

namespace Arjasco\Beanie\Strategies;

interface StrategyInterface
{
    /**
     * Get a connection from the pool.
     *
     * @param array $connections
     * @return \Arjasco\Beanie\Connection
     */
    public function getConnection($connections);
}