<?php

namespace Arjasco\Beanie;

class WatchList
{
    /**
     * Minimum watch list size
     *
     * @var int
     */
    const MINIMUM_SIZE = 1;

    /**
     * List of tubes.
     *
     * @var array
     */
    protected $tubes = ['default'];

    /**
     * Add tube to list.
     *
     * @param string $tube
     * @return void
     */
    public function add($tube)
    {
        $this->tube[] = $tube;
    }

    /**
     * Remove tube from list.
     *
     * @param string $tube
     * @return void
     */
    public function remove($tube)
    {
        $index = array_search($tube, $this->tubes);

        if ($index != false) {
            array_splice($this->tubes, $index, 1);
        }
    }

    /**
     * Check if a tube is on the watch list.
     *
     * @param string $tube
     * @return boolean
     */
    public function has($tube)
    {
        return in_array($tube, $this->tubes);
    }

    /**
     * Number of tubes in the watch list.
     *
     * @return int
     */
    public function count()
    {
        return count($this->all());
    }

    /**
     * Get all tubes on the watch list.
     *
     * @return array
     */
    public function all()
    {
        return $this->tubes;
    }
}
