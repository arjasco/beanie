<?php

namespace Arjasco\Sprout\Commands;

class PeekBuriedCommand implements Command
{
    /**
     * @inheritdoc
     */
    public function getLine()
    {
        return 'peek-buried';
    }

    /**
     * @inheritdoc
     */
    public function hasAdditionalData()
    {
        return false;
    }
}
