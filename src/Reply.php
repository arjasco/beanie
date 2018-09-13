<?php

namespace Arjasco\Beanie;

use Arjasco\Beanie\Exceptions\ServerException;
use Arjasco\Beanie\DataAware;

class Reply
{
    use DataAware;

    /**
     * Common server error replies.
     *
     * @var array
     */
    protected $errors = [
        'OUT_OF_MEMORY' => 'Server out of memory.',
        'INTERNAL_ERROR' => 'Internal server error.',
        'BAD_FORMAT' => 'Command has a bad format.',
        'UNKNOWN_COMMAND' => 'Command unknown.',
        'JOB_TOO_BIG' => 'Job exceeds the max-job-size.',
        'DRAINING' => 'Server is in "drain mode".',
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
     * Replies with job ids.
     *
     * @var array
     */
    protected $repliesWithJobIds = [
        'INSERTED',
        'RESERVED',
        'FOUND',
        'BURIED',
    ];

    /**
     * Command reply segments.
     *
     * @var array
     */
    protected $segments;

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
                return (int) $this->getSegment(1);

            case 'RESERVED':
            case 'FOUND':
                return (int) $this->getSegment(2);
        }
    }

    /**
     * Get a reply segment by index.
     *
     * @return int
     */
    public function getSegment($index = 0)
    {
        return $this->segments[$index] ?? null;
    }

    /**
     * Get the status.
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->getSegment(0);
    }

    /**
     * Get the job id.
     *
     * @return mixed
     */
    public function getId()
    {
        if (in_array($this->getStatus(), $this->repliesWithJobIds)) {
            return $this->getSegment(1);
        }

        return null;
    }
}
