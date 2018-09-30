<?php

namespace Arjasco\Sprout\Commands;

class ListTubesWatchedCommand implements Command
{
    /**
     * @inheritdoc
     */
    public function getLine()
    {
        return 'list-tubes-watched';
    }

    /**
     * @inheritdoc
     */
    public function hasAdditionalData()
    {
        return false;
    }
}
