<?php

namespace Arjasco\Sprout;

class PeekPending
{
    /**
     * Connection to beanstalk server(s)
     *
     * @var ConnectionInterface
     */
    protected $connection;

    /**
     * Create a new PeekPending instance.
     *
     * @param ConnectionInterface $connection
     */
    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Peek into a job.
     *
     * @param mixed $job
     * @return mixed
     */
    public function job($job)
    {
        $id = ($job instanceof Job) ? $job->getId() : (int) $job;

        $reply = $this->connection->send(
            new Commands\PeekCommand($id)
        );

        return $this->prepareResponse($reply);
    }

    /**
     * Peek into the next available job.
     *
     * @return mixed
     */
    public function ready()
    {
        $reply = $this->connection->send(
            new Commands\PeekReadyCommand
        );

        return $this->prepareResponse($reply);
    }

    /**
     * Peek into the shortest delayed job.
     *
     * @return mixed
     */
    public function delayed()
    {
        $reply = $this->connection->send(
            new Commands\PeekDelayedCommand
        );

        return $this->prepareResponse($reply);
    }

    /**
     * Peek into the next buried job.
     *
     * @return mixed
     */
    public function buried()
    {
        $reply = $this->connection->send(
            new Commands\PeekBuriedCommand
        );

        return $this->prepareResponse($reply);
    }

    /**
     * Prepare response.
     *
     * @param Reply $reply
     * @return void
     */
    protected function prepareResponse($reply)
    {
        if ($reply->getStatus() == 'NOT_FOUND') {
            return null;
        }

        return new Job($reply->getId(), $reply->getData());
    }
}
