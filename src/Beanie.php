<?php

namespace Arjasco\Beanie;

class Beanie
{
    /**
     * Default job priority.
     *
     * @var int
     */
    const DEFAULT_PRIORITY = 1024;

    /**
     * Default job delay.
     *
     * @var int
     */
    const DEFAULT_DELAY = 0;

    /**
     * Default job time to run.
     *
     * @var int
     */
    const DEFAULT_TTR = 60;

    /**
     * Connection to beanstalk server(s)
     *
     * @var ConnectionInterface
     */
    protected $connection;

    /**
     * Tube watch list.
     *
     * @var WatchList;
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
     * @param mixed $job
     * @param int $priority
     * @param int $delay
     * @param int $ttr
     * @return Reply
     */
    public function put(
        $job,
        $priority = self::DEFAULT_PRIORITY,
        $delay = self::DEFAULT_DELAY,
        $ttr = self::DEFAULT_TTR
    ) {
        if (! ($job instanceof Job)) {
            $job = new Job(null, (string) $job);
        }

        $cmd = new Commands\PutCommand($priority, $delay, $ttr);
        $cmd->setData($job->getData());

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
        $this->connection->send(
            new Commands\UseCommand($tube)
        );

        return $this;
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

        if ($this->watchList->count() == WatchList::MINIMUM_SIZE) {
            return $this;
        }

        foreach ($tubes as $tube) {
            if ($this->watchList->has($tube)) {
                $reply = $this->connection->send(
                    new Commands\IgnoreCommand($tube)
                );

                if ($reply->getStatus() == 'WATCHING') {
                    $this->watchList->remove($tube);
                }
            }
        }

        return $this;
    }

    /**
     * Reserve a job from the tube in use.
     *
     * @return Job|bool
     */
    public function reserve()
    {
        $reply = $this->connection->send(
            new Commands\ReserveCommand
        );

        if ($reply->getStatus() == 'RESERVED') {
            return new Job($reply->getId(), $reply->getData());
        }

        return false;
    }

    /**
     * Touch a job in the tube.
     *
     * @param Job $job
     * @return $this
     */
    public function touch(Job $job)
    {
        $this->connection->send(
            new Commands\TouchCommand($job->getId())
        );

        return $this;
    }

    /**
     * Release a job from the tube.
     *
     * @param Job $job
     * @param int $priority
     * @param int $delay
     * @return $this
     */
    public function release(
        Job $job,
        $priority = self::DEFAULT_PRIORITY,
        $delay = self::DEFAULT_DELAY
    ) {
        $this->connection->send(
            new Commands\ReleaseCommand($job->getId(), $priority, $delay)
        );

        return $this;
    }

    /**
     * Delete a job from the tube.
     *
     * @param Job $job
     * @return $this
     */
    public function delete(Job $job)
    {
        $this->connection->send(
            new Commands\DeleteCommand($job->getId())
        );

        return $this;
    }

    /**
     * Get server or tube stats.
     *
     * @param string|null $tube
     * @return array
     */
    public function stats($tube = null)
    {
        if (! is_null($tube)) {
            $cmd = new Commands\StatsTubeCommand($tube);
        } else {
            $cmd = new Commands\StatsCommand;
        }

        $reply = $this->connection->send($cmd);

        return YamlStatsParser::parseDictionary($reply->getData());
    }

    /**
     * Kick a job or number of jobs.
     *
     * @param mixed $value
     * @return void
     */
    public function kick($value)
    {
        if ($value instanceof Job) {
            $cmd = new Commands\KickJobCommand($value->getId());
        } else {
            $cmd = new Commands\KickCommand((int) $value);
        }

        $this->connection->send($cmd);
    }
}
