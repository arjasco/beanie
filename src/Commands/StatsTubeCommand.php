<?php

namespace Arjasco\Sprout\Commands;

class StatsTubeCommand implements Command
{
    /**
     * Tube name.
     *
     * @var string
     */
    protected $tube;

    /**
     * Create a new "stats-tube" command.
     *
     * @param string $tube
     */
    public function __construct($tube)
    {
        $this->tube = $tube;
    }

    /**
     * @inheritdoc
     */
    public function getLine()
    {
        return 'stats-tube ' . $this->tube;
    }

    /**
     * @inheritdoc
     */
    public function hasAdditionalData()
    {
        return false;
    }
}
