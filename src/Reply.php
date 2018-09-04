<?php

namespace Arjasco\Beanie;

use Arjasco\Beanie\Exceptions\ServerException;

class Reply
{
    /**
     * Common server error replies.
     *
     * @var array
     */
    protected $errors = [
        'OUT_OF_MEMORY' => 'Server out of memory.',
        'INTERNAL_ERROR' => 'Internal server error.',
        'BAD_FORMAT' => 'Command has a bad format.',
        'UNKNOWN_COMMAND' => 'Command unknown',
    ];

    /**
     * Replies with additional data.
     *
     * @var array
     */
    protected $repliesWithData = [
        'OK',
        'RESERVED',
        'FOUND',
    ];

    /**
     * Command reply segments.
     *
     * @var array
     */
    protected $segments;

    /**
     * Additional reply data.
     *
     * @var array
     */
    protected $data;

    /**
     * Create a new reply.
     *
     * @param string $contents
     * @throws \Arjasco\Beanie\Exceptions\ServerException
     */
    public function __construct($contents)
    {
        // Get the reply segments.
        // [0] Will always be the reply status
        // [1], [2] will be the number of bytes if additional data or the id of the job.
        $this->segments = explode(' ', trim($contents));

        $status = $this->getStatus();

        if (isset($this->errors[$status])) {
            throw new ServerException($this->errors[$status]);
        }
    }

    /**
     * Check if the reply is followed by data.
     *
     * @return bool
     */
    public function hasAdditionalData()
    {
        return in_array($this->getStatus(), $this->repliesWithData);
    }

    /**
     * Get the number of bytes the follow data has.
     * OK -> index 1
     * RESERVED, FOUND -> index 2
     *
     * @return int
     */
    public function getBytes()
    {
        switch ($this->getStatus()) {
            case 'OK':
                return (int) $this->segments[1];

            case 'RESERVED':
            case 'FOUND':
                return (int) $this->segments[2];
        }
    }

    /**
     * Get the job id.
     *
     * @return int
     */
    public function getId()
    {
        if ($this->getStatus() == 'OK' || ! isset($this->segments[1])) {
            return null;
        }

        return $this->segments[1];
    }

    /**
     * Get the job id.
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->segments[0];
    }

    /**
     * Set the additional data.
     *
     * @param string $data
     * @return mixed
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * Get the additional data.
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }
}