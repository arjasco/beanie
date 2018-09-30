<?php

namespace Tests\Integration;

use PHPUnit\Framework\TestCase;
use Arjasco\Sprout\Sprout;
use Arjasco\Sprout\Factory;
use Arjasco\Sprout\Job;
use Arjasco\Sprout\Exceptions\SocketException;

class SproutTest extends TestCase
{
    protected $sprout;

    public function setUp()
    {
        try {
            $this->restartBeanstalkd();
            $this->sprout = Factory::make();
        } catch (SocketException $e) {
            $this->markTestSkipped('No connection to beanstalk available.');
        }
    }

    public function test_that_it_can_add_a_job_to_the_default_tube()
    {
        $job = $this->sprout->put('test data');

        $this->assertInstanceOf(Job::class, $job);
        $this->assertNotNull($job->getId());
    }

    public function test_that_it_can_reserve_a_job_from_the_default_tube()
    {
        $this->sprout->put('data for reserve');

        $job = $this->sprout->reserve();

        $this->assertInstanceOf(Job::class, $job);
        $this->assertNotNull($job->getId());
        $this->assertEquals('data for reserve', $job->getData());
    }

    public function test_that_it_can_peek_a_job_from_the_default_tube()
    {
        $this->sprout->put('data for peek');

        $job = $this->sprout->peek()->ready();

        $this->assertInstanceOf(Job::class, $job);
        $this->assertNotNull($job->getId());
        $this->assertEquals('data for peek', $job->getData());
    }

    public function test_that_it_can_delete_a_job_from_the_default_tube()
    {
        $this->sprout->put('data for delete');

        $job = $this->sprout->peek()->ready();

        $this->assertInstanceOf(Job::class, $job);
        $this->assertNotNull($job->getId());
        $this->assertEquals('data for delete', $job->getData());

        $this->sprout->delete($job);

        $job = $this->sprout->peek()->ready();

        $this->assertNull($job);
    }

    public function test_that_it_can_use_a_tube()
    {
        $this->sprout->use('test-tube');

        $this->assertEquals('test-tube', $this->sprout->currentTube());

        $this->sprout->use('default');

        $this->assertEquals('default', $this->sprout->currentTube());
    }

    public function test_that_it_can_watch_tubes()
    {
        $this->sprout->use('test-tube')->put('data on test-tube');
        $this->sprout->use('default')->put('data on default');

        $job = $this->sprout->watch(['test-tube', 'default'])->reserve();

        $this->assertInstanceOf(Job::class, $job);
        $this->assertNotNull($job->getId());
        $this->assertEquals('data on test-tube', $job->getData());

        $this->sprout->delete($job);

        $job = $this->sprout->reserve();

        $this->assertInstanceOf(Job::class, $job);
        $this->assertNotNull($job->getId());
        $this->assertEquals('data on default', $job->getData());
    }

    public function test_that_it_can_ignore_tubes()
    {
        $this->sprout->watch(['tube1', 'tube2']);

        $this->assertSame(['default', 'tube1', 'tube2'], $this->sprout->watchedTubes(true));

        $this->sprout->ignore('tube1');

        $this->assertSame(['default', 'tube2'], $this->sprout->watchedTubes(true));

        $this->sprout->ignore('tube2');

        $this->assertSame(['default'], $this->sprout->watchedTubes(true));
    }

    public function test_that_it_can_bury_a_job()
    {
        $this->sprout->put('data to bury');

        $this->assertNull($this->sprout->peek()->buried());

        $this->sprout->bury($this->sprout->reserve());

        $job = $this->sprout->peek()->buried();

        $this->assertInstanceOf(Job::class, $job);
        $this->assertNotNull($job->getId());
        $this->assertEquals('data to bury', $job->getData());
    }

    public function test_that_it_can_kick_a_job()
    {
        $job = $this->sprout->put('data to kick');

        $this->sprout->bury($this->sprout->reserve());

        $this->assertNull($this->sprout->peek()->ready());

        $this->sprout->kickJob($job);

        $job = $this->sprout->reserve();

        $this->assertInstanceOf(Job::class, $job);
        $this->assertNotNull($job->getId());
        $this->assertEquals('data to kick', $job->getData());
    }

    public function test_that_it_can_release_a_job_with_delay()
    {
        $this->sprout->put('data to release');

        // 30 second delay
        $this->sprout->release($this->sprout->reserve(), Sprout::DEFAULT_PRIORITY, 30);

        $job = $this->sprout->peek()->delayed();

        $this->assertInstanceOf(Job::class, $job);
        $this->assertNotNull($job->getId());
        $this->assertEquals('data to release', $job->getData());
    }

    public function test_that_it_can_return_an_array_of_server_stats()
    {
        $stats = $this->sprout->stats();

        $this->assertTrue(is_array($stats));
        $this->assertArrayHasKey('current-jobs-urgent', $stats);
        $this->assertArrayHasKey('current-jobs-ready', $stats);
        $this->assertArrayHasKey('current-jobs-reserved', $stats);
        $this->assertArrayHasKey('current-jobs-buried', $stats);
    }

    public function test_that_it_can_return_an_array_of_tube_stats()
    {
        $stats = $this->sprout->stats('default');

        $this->assertTrue(is_array($stats));
        $this->assertArrayHasKey('total-jobs', $stats);
        $this->assertArrayHasKey('current-using', $stats);
        $this->assertArrayHasKey('current-watching', $stats);
        $this->assertArrayHasKey('pause', $stats);
    }

    public function test_that_it_can_pause_a_tube()
    {
        $this->assertTrue($this->sprout->pauseTube('default', 30));

        $stats = $this->sprout->stats('default');
        $this->assertArrayHasKey('pause', $stats);
        $this->assertEquals($stats['pause'], 30);
    }

    protected function restartBeanstalkd()
    {
        exec('sh ' . __DIR__ . '/launch.sh');
    }
}
