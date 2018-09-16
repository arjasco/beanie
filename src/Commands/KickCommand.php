<?php

namespace Arjasco\Beanie\Commands;

class KickJobCommand implements Command
{
    /**
     * Number of jobs to kick.
     *
     * @var int
     */
    protected $bound;

    /**
     * Create a new "kick" command.
     *
     * @param int $bound
     */
    public function __construct($bound)
    {
        $this->bound = $bound;
    }

    /**
     * @inheritdoc
     */
    public function getLine()
    {
        return 'kick ' . $this->bound;
    }

    /**
     * @inheritdoc
     */
    public function hasAdditionalData()
    {
        return false;
    }
}
