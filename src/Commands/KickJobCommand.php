<?php

namespace Arjasco\Beanie\Commands;

class KickJobCommand implements Command
{
    /**
     * Job id.
     *
     * @var int
     */
    protected $id;

    /**
     * Create a new "kick-job" command.
     *
     * @param int $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @inheritdoc
     */
    public function getLine()
    {
        return 'kick-job ' . $this->id;
    }

    /**
     * @inheritdoc
     */
    public function hasAdditionalData()
    {
        return false;
    }
}
