<?php

namespace Arjasco\Beanie\Commands;

class IgnoreCommand implements Command
{
    /**
     * Tube name.
     *
     * @var string
     */
    protected $tube;

    /**
     * Create a new "ignore" command.
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
        return 'ignore ' . $this->tube;
    }

    /**
     * @inheritdoc
     */
    public function hasAdditionalData()
    {
        return false;
    }
}