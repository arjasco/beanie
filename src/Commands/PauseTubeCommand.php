<?php

namespace Arjasco\Sprout\Commands;

class PauseTubeCommand implements Command
{
    /**
     * Tube name.
     *
     * @var string
     */
    protected $tube;

    /**
     * Number of seconds to pause the tube.
     *
     * @var string
     */
    protected $delay;

    /**
     * Create a new "Pause Tube" command.
     *
     * @param string $tube
     * @param int $delay
     */
    public function __construct($tube, $delay)
    {
        $this->tube = $tube;
        $this->delay = $delay;
    }

    /**
     * @inheritdoc
     */
    public function getLine()
    {
        return sprintf(
            "pause-tube %s %s",
            $this->tube,
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
