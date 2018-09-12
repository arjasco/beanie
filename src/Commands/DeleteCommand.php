<?php

namespace Arjasco\Beanie\Commands;

class DeleteCommand implements Command
{
    /**
     * Job id.
     *
     * @var int
     */
    protected $id;

    /**
     * Create a new "delete" command.
     *
     * @param int $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @inheritdoc
     */
    public function getLine()
    {
        return 'delete ' . $this->id;
    }

    /**
     * @inheritdoc
     */
    public function hasAdditionalData()
    {
        return false;
    }
}