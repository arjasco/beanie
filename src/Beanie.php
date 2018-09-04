<?php

namespace Arjasco\Beanie;

class Beanie
{
    /**
     * Connection to beanstalk server(s)
     *
     * @var ConnectionInterface
     */
    protected $connection;

    /**
     * Create a new Beanie instance.
     *
     * @param ConnectionInterface $connection
     */
    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    /**
     * "Put" a job into a tube.
     *
     * @param Job $job
     * @return void
     */
    public function put(Job $job)
    {
        $cmd = new Commands\PutCommand(
            $job->getPriority(),
            $job->getDelay(),
            $job->getTimeToRun()
        );

        $cmd->setData($job->toJsonPayload());

        $this->connection->send()
    }
}