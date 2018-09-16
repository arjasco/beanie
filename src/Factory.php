<?php

namespace Arjasco\Beanie;

class Factory
{
    /**
     * Create a new Beanie instance.
     *
     * @param string $address
     * @param integer $port
     * @param boolean $persistent
     * @param integer $timeout
     * @return Beanie
     */
    public static function make($address, $port, $persistent = false, $timeout = 0)
    {
        return new Beanie(
            new Connection(
                new Socket($address, $port, $persistent, $timeout)
            )
        );
    }
}
