<?php

namespace Arjasco\Beanie\Commands;

interface Command 
{
    /**
     * Get the command string to send.
     *
     * @return string
     */
    public function getLine();

    /**
     * Check if the command has additional data to send.
     *
     * @return bool
     */
    public function hasAdditionalData();
}