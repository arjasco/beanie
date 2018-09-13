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
     * @param mixed $id
     * @param mixed $data
     */
    public function __construct($id = null, $data = null)
    {
        $this->id = $id;
        $this->data = $data;
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
     * @return string
     */
    public function toJson()
    {
        return json_encode([
            'id' => $this->id,
            'data' => $this->data,
        ]);
    }

    /**
     * Get the job id.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
}
