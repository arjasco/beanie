<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Arjasco\Sprout\Connection;
use Arjasco\Sprout\Socket;
use Arjasco\Sprout\Commands\Command;
use Arjasco\Sprout\Reply;
use Arjasco\Sprout\DataAware;
use Mockery as m;

class ConnectionTest extends TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function test_it_can_send_commands_without_additional_data()
    {
        $connection = new Connection($socketMock = m::mock(Socket::class));

        $socketMock->shouldReceive('write')->once()->with("test-cmd\r\n");
        $socketMock->shouldReceive('readLine')->once()->andReturn("TEST\r\n");

        $reply = $connection->send(new TestCommandWithoutData);

        $this->assertInstanceOf(Reply::class, $reply);
        $this->assertEquals('TEST', $reply->getStatus());
    }

    public function test_it_can_send_commands_with_additional_data()
    {
        $connection = new Connection($socketMock = m::mock(Socket::class));

        $socketMock->shouldReceive('write')->once()->with("test-cmd\r\n");
        $socketMock->shouldReceive('write')->once()->with("test-data-to-send\r\n");
        $socketMock->shouldReceive('readLine')->once()->andReturn("TEST-WITH-DATA\r\n");

        $cmd = new TestCommandWithData;
        $cmd->setData('test-data-to-send');

        $reply = $connection->send($cmd);

        $this->assertInstanceOf(Reply::class, $reply);
        $this->assertEquals('TEST-WITH-DATA', $reply->getStatus());
    }

    public function test_it_can_send_commands_and_return_data()
    {
        $connection = new Connection($socketMock = m::mock(Socket::class));

        $socketMock->shouldReceive('write')->once()->with("test-cmd\r\n");
        $socketMock->shouldReceive('write')->once()->with("test-data-to-send\r\n");
        $socketMock->shouldReceive('readLine')->once()->andReturn("FOUND 1 13\r\n");
        $socketMock->shouldReceive('read')->once()->with(13)->andReturn('returned-data');

        // CRLF length
        $socketMock->shouldReceive('read')->once()->with(2);

        $cmd = new TestCommandWithData;
        $cmd->setData('test-data-to-send');

        $reply = $connection->send($cmd);

        $this->assertInstanceOf(Reply::class, $reply);
        $this->assertEquals('FOUND', $reply->getStatus());
        $this->assertEquals('1', $reply->getId());
        $this->assertEquals('13', $reply->getBytes());
        $this->assertEquals('returned-data', $reply->getData());
    }
}

class TestCommandWithoutData implements Command
{
    public function getLine()
    {
        return 'test-cmd';
    }

    public function hasAdditionalData()
    {
        return false;
    }
}

class TestCommandWithData implements Command
{
    use DataAware;

    public function getLine()
    {
        return 'test-cmd';
    }

    public function hasAdditionalData()
    {
        return true;
    }
}
