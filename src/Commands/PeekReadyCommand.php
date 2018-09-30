<?php

namespace Arjasco\Sprout\Commands;

class PeekReadyCommand implements Command
{
    /**
     * @inheritdoc
     */
    public function getLine()
    {
        return 'peek-ready';
    }

    /**
     * @inheritdoc
     */
    public function hasAdditionalData()
    {
        return false;
    }
}
