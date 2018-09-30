<?php

namespace Arjasco\Sprout;

class Factory
{
    /**
     * Create a new Sprout instance.
     *
     * @param string $address
     * @param integer $port
     * @param boolean $persistent
     * @param integer $timeout
     * @return Sprout
     */
    public static function make(
        $address = 'localhost',
        $port = '11300',
        $persistent = false,
        $timeout = 0
    ) {
        return new Sprout(
            new Connection(
                new Socket($address, $port, $persistent, $timeout)
            )
        );
    }
}
