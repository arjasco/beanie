<?php

namespace Arjasco\Beanie;

class Job
{
    /**
     * Job id.
     *
     * @var int
     */
    protected $id;

    /**
     * Job priority.
     *
     * @var int
     */
    protected $priority = 1000;

    /**
     * Job delay in seconds.
     *
     * @var int
     */
    protected $delay = 0;

    /**
     * Job "time to run" in seconds.
     *
     * @var int
     */
    protected $ttr = 60;

    /**
     * Job data.
     *
     * @var mixed
     */
    protected $data;

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
     * Set the priority.
     *
     * @param int $value
     * @return $this
     */
    public function setPriority($value)
    {
        $this->priority = $value;

        return $this;
    }

    /**
     * Set the delay.
     *
     * @param int $value
     * @return $this
     */
    public function setDelay($seconds)
    {
        $this->delay = $seconds;

        return $this;
    }

    /**
     * Set the time to run.
     *
     * @param int $value
     * @return $this
     */
    public function setTimeToRun($seconds)
    {
        $this->ttr = $seconds;

        return $this;
    }

    /**
     * Set the job data.
     *
     * @param int $value
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get the priority.
     *
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set the delay.
     *
     * @return int
     */
    public function getDelay()
    {
        return $this->delay;
    }

    /**
     * Set the time to run.
     *
     * @return int
     */
    public function getTimeToRun()
    {
        return $this->ttr;
    }

    /**
     * Get the job data.
     *
     * @param int $value
     * @return $this
     */
    public function getData($data)
    {
        return $this->data;
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
            'priority' => $this->priority,
            'delay' => $this->delay,
            'ttr' => $this->ttr,
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
            'data' => $this->data,
        ]);
    }
}