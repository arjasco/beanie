<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Arjasco\Sprout\Job;

class JobTest extends TestCase
{
    public function test_it_can_set_data()
    {
        $job = new Job(null, 'Some data');

        $this->assertSame('Some data', $job->getData());

        $job->setData(['test' => 'data']);

        $this->assertSame(['test' => 'data'], $job->getData());
    }

    public function test_it_can_set_the_id()
    {
        $job = new Job(123);

        $this->assertEquals(123, $job->getId());
    }

    public function test_it_can_provide_a_json_payload()
    {
        $job = new Job;

        $job->setData(['test' => 'data']);

        $this->assertEquals('{"id":null,"data":{"test":"data"}}', $job->toJson());
    }

    public function test_it_can_provide_an_array_payload()
    {
        $job = new Job;

        $job->setData(['test' => 'data']);

        $this->assertSame(['id' => null, 'data' => ['test' => 'data']], $job->toArray());
    }
}
