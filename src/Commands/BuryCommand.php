<?php

namespace Arjasco\Beanie\Commands;

class BuryCommand implements Command
{
    /**
     * Job id.
     *
     * @var int
     */
    protected $id;

    /**
     * Job priority.
     *
     * @var int
     */
    protected $priority;

    /**
     * Create a new "bury" command.
     *
     * @param int $id
     * @param int $priority
     */
    public function __construct($id, $priority, $delay)
    {
        $this->id = $id;
        $this->priority = $priority;
    }

    /**
     * @inheritdoc
     */
    public function getLine()
    {
        return sprintf(
            "bury %s %s",
            $this->id,
            $this->priority
        );
    }

    /**
     * @inheritdoc
     */
    public function hasAdditionalData()
    {
        return false;
    }
}