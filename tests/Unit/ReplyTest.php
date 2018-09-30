<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Arjasco\Sprout\Reply;
use Arjasco\Sprout\Exceptions\ServerException;

class ReplyTest extends TestCase
{
    /**
     * @dataProvider errorValues
     */
    public function test_it_throws_a_server_exception_when_given_error_reply($error, $message)
    {
        $this->expectException(ServerException::class);
        $this->expectExceptionMessage($message);
        new Reply($error);
    }

    public function test_it_can_get_the_reply_status()
    {
        $reply = new Reply('FOUND');

        $this->assertEquals('FOUND', $reply->getStatus());
    }

    public function test_it_can_get_the_reply_id()
    {
        $reply = new Reply('FOUND 1');

        $this->assertEquals('1', $reply->getId());
    }

    public function test_it_can_get_the_reply_bytes()
    {
        $reply = new Reply('FOUND 1 100');

        $this->assertEquals('100', $reply->getBytes());
    }

    public function test_it_can_get_reply_segment_at_index()
    {
        $reply = new Reply('FOUND 1 100');

        $this->assertEquals('FOUND', $reply->getSegment(0));
        $this->assertEquals('1', $reply->getSegment(1));
        $this->assertEquals('100', $reply->getSegment(2));
        $this->assertNull($reply->getSegment(3));
    }

    /**
     * @dataProvider additionalDataReplies
     */
    public function test_if_can_check_if_there_is_additional_data($command, $expected)
    {
        $reply = new Reply($command);

        $this->assertEquals($expected, $reply->hasAdditionalData());
    }

    public function errorValues()
    {
        return [
            ['OUT_OF_MEMORY', 'Server out of memory.'],
            ['INTERNAL_ERROR', 'Internal server error.'],
            ['BAD_FORMAT', 'Command has a bad format.'],
            ['UNKNOWN_COMMAND', 'Command unknown.'],
            ['JOB_TOO_BIG', 'Job exceeds the max-job-size.'],
            ['DRAINING', 'Server is in "drain mode".'],
        ];
    }

    public function additionalDataReplies()
    {
        return [
            ['FOUND 1 100', true],
            ['OK 100', true],
            ['RESERVED 1 100', true],
            ['NOT_FOUND', false],
            ['DELETED', false],
            ['KICKED 10', false],
        ];
    }
}
