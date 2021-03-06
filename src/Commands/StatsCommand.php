<?php

namespace Arjasco\Sprout\Commands;

class StatsCommand implements Command
{
    /**
     * @inheritdoc
     */
    public function getLine()
    {
        return 'stats';
    }

    /**
     * @inheritdoc
     */
    public function hasAdditionalData()
    {
        return false;
    }
}
