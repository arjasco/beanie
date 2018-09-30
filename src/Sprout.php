<?php

namespace Arjasco\Sprout;

class Sprout
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
     * Create a new Sprout instance.
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
        $cmd = new Commands\PutCommand($priority, $delay, $ttr);

        $cmd->setData($data = $this->getJobData($job));

        $reply = $this->connection->send($cmd);

        return new Job($reply->getId(), $data);
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
     * Bury a job.
     *
     * @param mixed $job
     * @param integer $priority
     * @return bool
     */
    public function bury($job, $priority = self::DEFAULT_PRIORITY)
    {
        $reply = $this->connection->send(
            new Commands\BuryCommand($this->getJobId($job), $priority)
        );

        return $reply->getStatus() == 'BURIED';
    }

    /**
     * Touch a job in the tube.
     *
     * @param mixed $job
     * @return $this
     */
    public function touch($job)
    {
        $this->connection->send(
            new Commands\TouchCommand($this->getJobId($job))
        );

        return $this;
    }

    /**
     * Release a job back into the ready queue.
     *
     * @param mixed $job
     * @param int $priority
     * @param int $delay
     * @return $this
     */
    public function release(
        $job,
        $priority = self::DEFAULT_PRIORITY,
        $delay = self::DEFAULT_DELAY
    ) {
        $this->connection->send(
            new Commands\ReleaseCommand($this->getJobId($job), $priority, $delay)
        );

        return $this;
    }

    /**
     * Delete a job from the tube.
     *
     * @param mixed $job
     * @return $this
     */
    public function delete($job)
    {
        $this->connection->send(
            new Commands\DeleteCommand($this->getJobId($job))
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
     * Kick a number of jobs.
     *
     * @param mixed $value
     * @return void
     */
    public function kick($value)
    {
        $this->connection->send(
            new Commands\KickCommand((int) $value)
        );

        return $this;
    }

    /**
     * Kick a number of jobs.
     *
     * @param mixed $job
     * @return bool
     */
    public function kickJob($job)
    {
        $reply = $this->connection->send(
            new Commands\KickJobCommand($this->getJobId($job))
        );

        return $reply->getStatus() == 'KICKED';
    }

    /**
     * Peek into a job.
     *
     * @return PeekPending
     */
    public function peek()
    {
        return new PeekPending($this->connection);
    }

    /**
     * Pause a tube.
     *
     * @param string $tube
     * @param int $delay
     * @return bool
     */
    public function pauseTube($tube, $delay)
    {
        $reply = $this->connection->send(
            new Commands\PauseTubeCommand($tube, $delay)
        );

        return $reply->getStatus() == 'PAUSED';
    }

    /**
     * Get the name of the current tube being used.
     *
     * @return string
     */
    public function currentTube()
    {
        $reply = $this->connection->send(
            new Commands\ListTubeUsedCommand
        );

        return $reply->getSegment(1);
    }

    /**
     * Get and array of tubes being watched
     *
     * @return string
     */
    public function watchedTubes($sendCommand = false)
    {
        if (! $sendCommand) {
            return $this->watchList->all();
        }

        $reply = $this->connection->send(
            new Commands\ListTubesWatchedCommand
        );

        return YamlStatsParser::parseList($reply->getData());
    }

    /**
     * Return job data.
     *
     * @param mixed $job
     * @return string
     */
    protected function getJobData($job)
    {
        return ($job instanceof Job) ? $job->getData() : (string) $job;
    }

    /**
     * Return job id.
     *
     * @param mixed $job
     * @return int
     */
    protected function getJobId($job)
    {
        return ($job instanceof Job) ? $job->getId() : (int) $job;
    }
}
