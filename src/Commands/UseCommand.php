<?php

namespace Arjasco\Beanie\Commands;

use LengthException;

class UseCommand implements Command
{
    /**
     * Max tube name length in bytes.
     *
     * @var int
     */
    const MAX_TUBE_NAME_LENGTH = 200;

    /**
     * Tube name.
     *
     * @var string
     */
    protected $tube;

    /**
     * Create a new "use" command.
     *
     * @param string $tube
     */
    public function __construct($tube)
    {
        if (strlen($tube) > self::MAX_TUBE_NAME_LENGTH) {
            throw new LengthException(sprintf(
                "The given tube name [%s] must be less than %s bytes", 
                $tube, 
                self::MAX_TUBE_NAME_LENGTH
            ));
        }

        $this->tube = $tube;
    }

    /**
     * @inheritdoc
     */
    public function getLine()
    {
        return 'use ' . $this->tube;
    }

    /**
     * @inheritdoc
     */
    public function hasAdditionalData()
    {
        return false;
    }
}