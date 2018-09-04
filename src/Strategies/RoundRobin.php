<?php

namespace Arjasco\Beanie\Strategies;

class RoundRobin implements StrategyInterface
{
    /**
     * Position.
     *
     * @var integer
     */
    protected $position = -1;

    /**
     * @inheritdoc
     */
    public function getConnection($connections)
    {
        $max = count($connections);

        $conns = array_values($connections);

        $this->position = ($this->position + 1) % $max;

        return $conns[$this->position];
    }
}