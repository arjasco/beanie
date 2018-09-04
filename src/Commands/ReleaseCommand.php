<?php

namespace Arjasco\Beanie\Commands;

class ReleaseCommand extends Command
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
     * Job delay.
     *
     * @var int
     */
    protected $delay;

    /**
     * Create a new "release" command.
     *
     * @param int $id
     * @param int $priority
     * @param int $delay
     */
    public function __construct($id, $priority, $delay)
    {
        $this->id = $id;
        $this->priority = $priority;
        $this->delay = $delay;
    }

    /**
     * @inheritdoc
     */
    public function getLine()
    {
        return sprintf(
            "release %s %s %s",
            $this->id,
            $this->priority,
            $this->delay
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