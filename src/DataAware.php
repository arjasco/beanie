<?php

namespace Arjasco\Beanie;

trait DataAware
{
    /**
     * Data payload.
     *
     * @var string
     */
    protected $data;

    /**
     * Set the data.
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
     * Get the data.
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }
}