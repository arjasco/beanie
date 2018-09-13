<?php

namespace Arjasco\Beanie\Commands;

class WatchCommand implements Command
{
    /**
     * Tube name.
     *
     * @var string
     */
    protected $tube;

    /**
     * Create a new "watch" command.
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
        return 'watch ' . $this->tube;
    }

    /**
     * @inheritdoc
     */
    public function hasAdditionalData()
    {
        return false;
    }
}
