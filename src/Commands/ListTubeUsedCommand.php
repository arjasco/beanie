<?php

namespace Arjasco\Sprout\Commands;

class ListTubeUsedCommand implements Command
{
    /**
     * @inheritdoc
     */
    public function getLine()
    {
        return 'list-tube-used';
    }

    /**
     * @inheritdoc
     */
    public function hasAdditionalData()
    {
        return false;
    }
}
