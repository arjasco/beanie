<?php

namespace Arjasco\Beanie;

use Arjasco\Beanie\DataAware;

class Job
{
    use DataAware;

    /**
     * Job id.
     *
     * @var int
     */
    protected $id;

    /**
     * Create a new job
     *
     * @param mixed $payload
     */
    public function __construct($id = null)
    {
       $this->id = $id;
    }

    /**
     * Get the array representation of the job.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'data' => $this->data,
        ];
    }

    /**
     * JSON encoded payload.
     *
     * @return void
     */
    public function toJson()
    {
        return json_encode([
            'id' => $this->id,
            'data' => $this->data,
        ]);
    }
}