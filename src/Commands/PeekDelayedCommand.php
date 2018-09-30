<?php

namespace Arjasco\Sprout\Commands;

class PeekDelayedCommand implements Command
{
    /**
     * @inheritdoc
     */
    public function getLine()
    {
        return 'peek-delayed';
    }

    /**
     * @inheritdoc
     */
    public function hasAdditionalData()
    {
        return false;
    }
}
