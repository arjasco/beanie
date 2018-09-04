<?php

namespace Arjasco\Beanie\Commands;

class StatsCommand extends Command 
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