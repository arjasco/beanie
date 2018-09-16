<?php

namespace Arjasco\Beanie;

use Arjasco\Beanie\Commands\Command;

interface ConnectionInterface
{
    /**
     * Send a command to the server.
     *
     * @param Command $command
     * @return Reply
     */
    public function send(Command $command);
}
