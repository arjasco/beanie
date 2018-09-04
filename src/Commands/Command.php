<?php

namespace Arjasco\Beanie\Commands;

abstract class Command
{
    /**
     * The command data
     *
     * @var string
     */
    protected $data;

    /**
     * Set the command data.
     *
     * @param string $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get the command data.
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Get the command string to send.
     *
     * @return string
     */
    abstract public function getLine();

    /**
     * Check if the command has additional data to send.
     *
     * @return bool
     */
    abstract public function hasAdditionalData();
}