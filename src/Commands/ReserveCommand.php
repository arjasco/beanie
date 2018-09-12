<?php

namespace Arjasco\Beanie\Commands;

use LengthException;

class ReserveCommand implements Command
{
    /**
     * Reserve timeout.
     *
     * @var int
     */
    protected $timeout;

    /**
     * Create a new "reserve" command.
     *
     * @param string $timeout
     */
    public function __construct($timeout = null)
    {
        $this->timeout = $timeout;
    }

    /**
     * @inheritdoc
     */
    public function getLine()
    {
        if ($this->timeout) {
            return 'reserve-with-timeout ' . $this->timeout;
        }

        return 'reserve';
    }

    /**
     * @inheritdoc
     */
    public function hasAdditionalData()
    {
        return false;
    }
}