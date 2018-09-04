<?php

namespace Arjasco\Beanie;

use \Arjasco\Beanie\Commands\Command;

interface CommandSenderInterface
{
    /**
     * Send a command to the server.
     *
     * @param \Arjasco\Beanie\Commands\Command $command
     * @return \Arjasco\Beanie\Reply
     */
    public function send(Command $command);
}