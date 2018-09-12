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
     * Tube watch list.
     *
     * @var \Arjasco\Beanie\WatchList;
     */
    protected $watchList;

    /**
     * Create a new Beanie instance.
     *
     * @param ConnectionInterface $connection
     */
    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
        $this->watchList = new WatchList;
    }

    /**
     * Put a job into a tube.
     *
     * @param \Arjasco\Job $job
     * @param int $priority
     * @param int $delay
     * @param int $ttr
     * @return \Arjasco\Beanie\Reply
     */
    public function put(Job $job, $priority, $delay, $ttr)
    {
        $cmd = (new Commands\PutCommand(
            $priority, $delay, $ttr
        ))->setData(
            $job->getData()
        );

        $reply = $this->connection->send($cmd);
    }

    /**
     * The tube to use.
     *
     * @param string $tube
     * @return $this
     */
    public function use($tube)
    {
        $reply = $this->connection->send(
            new Commands\UseCommand($tube)
        );

        $job = new Job($reply->getStatus());

    }

    /**
     * Watch any number of tubes.
     *
     * @param mixed $tubes
     * @return $this
     */
    public function watch($tubes)
    {
        $tubes = ! is_array($tubes) ? [$tubes] : $tubes;

        foreach ($tubes as $tube) {
            if (! $this->watchList->has($tube)) {
                $reply = $this->connection->send(
                    new Commands\WatchCommand($tube)
                );

                if ($reply->getStatus() == 'WATCHING') {
                    $this->watchList->add($tube);
                }
            }
        }

        return $this;
    }

    /**
     * Ignore any number of tubes.
     *
     * @param mixed $tubes
     * @return $this
     */
    public function ignore($tubes)
    {
        $tubes = !is_array($tubes) ? [$tubes] : $tubes;

        // Ignoring the only tube in the watch list is not allowed.
        if ($this->watchList->count() < 2) {
            return $this;
        }

        foreach ($tubes as $tube) {
            if ($this->watchList->has($tube)) {
                $reply = $this->connection->send(
                    new Commands\IgnoreCommand($tube)
                );

                if ($reply->getStatus() == 'WATCHING') {
                    $this->watchList->add($tube);
                }
            }
        }

        return $this;
    }

    /**
     * Reverse a job from the tube.
     *
     * @param string $tube
     * @return $this
     */
    public function reserve($tube)
    {
        $reply = $this->connection->send(
            new Commands\UseCommand($tube)
        );

        return $this;
    }
}