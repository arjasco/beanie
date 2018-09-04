<?php

namespace Arjasco\Beanie;

trait ConnectionAware
{
    /**
     * Connection name.
     *
     * @var string
     */
    protected $connectionName;

    /**
     * Set connection name;
     *
     * @param string $name
     * @return void
     */
    public function setConnectionName($name)
    {
        $this->connectionName = $name;
    }

    /**
     * Get connection name
     *
     * @param string $name
     * @return void
     */
    public function getConnectionName()
    {
        return $this->connectionName;
    }
}