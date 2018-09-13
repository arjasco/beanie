<?php

namespace Arjasco\Beanie\Commands;

use Arjasco\Beanie\DataAware;

class PutCommand implements Command
{
    use DataAware;
    
    /**
     * Job priority.
     *
     * @var int
     */
    protected $priority;

    /**
     * Job delay in seconds.
     *
     * @var int
     */
    protected $delay;

    /**
     * Job "time to run" in seconds.
     *
     * @var int
     */
    protected $ttr;

    /**
     * Create a new "put" command.
     *
     * @param int $priority
     * @param int $delay
     * @param int $ttr
     */
    public function __construct($priority, $delay, $ttr)
    {
        $this->priority = $priority;
        $this->delay = $delay;
        $this->ttr = $ttr;
    }

    /**
     * @inheritdoc
     */
    public function getLine()
    {
        return sprintf(
            "put %s %s %s %s",
            $this->priority,
            $this->delay,
            $this->ttr,
            strlen($this->getData())
        );
    }

    /**
     * @inheritdoc
     */
    public function hasAdditionalData()
    {
        return true;
    }
}
