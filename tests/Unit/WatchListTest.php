<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Arjasco\Sprout\WatchList;

class WatchListTest extends TestCase
{
    public function test_it_can_get_all_tubes()
    {
        $list = new WatchList;

        $this->assertSame(['default'], $list->all());
    }

    public function test_it_can_add_a_tube_to_the_list()
    {
        $list = new WatchList;

        $list->add('emails');

        $this->assertSame(['default', 'emails'], $list->all());
    }

    public function test_it_can_remove_a_tube_from_the_list()
    {
        $list = new WatchList;

        $list->add('emails');
        $list->add('files');

        $this->assertSame(['default', 'emails', 'files'], $list->all());

        $list->remove('emails');

        $this->assertSame(['default', 'files'], $list->all());

        $list->remove('files');

        $this->assertSame(['default'], $list->all());
    }

    public function test_it_can_count_all_tubes_in_the_list()
    {
        $list = new WatchList;

        $list->add('emails');
        $list->add('files');

        // Default tube is always in the list initially
        $this->assertEquals(3, $list->count());
    }

    public function test_it_can_check_if_a_tube_is_present_in_the_list()
    {
        $list = new WatchList;

        $this->assertFalse($list->has('emails'));

        $list->add('emails');

        $this->assertTrue($list->has('emails'));
    }
}
