<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Arjasco\Sprout\YamlStatsParser;

class YamlStatsParserTest extends TestCase
{
    public function test_it_can_parse_a_yaml_dictionary_to_an_array()
    {
        $input = file_get_contents(__DIR__ . '/../Fixtures/stats.yaml');
        $output = YamlStatsParser::parseDictionary($input);

        $this->assertSame($this->expectedDictionaryOutput(), $output);
    }

    public function test_it_can_parse_a_list_of_strings_to_an_array()
    {
        $input = file_get_contents(__DIR__ . '/../Fixtures/tube-list.yaml');
        $output = YamlStatsParser::parseList($input);

        $this->assertSame(['default', 'tube1', 'tube2', 'tube3', 'tube-test'], $output);
    }

    public function expectedDictionaryOutput()
    {
        return [
            'current-jobs-urgent' => 10000,
            'current-jobs-ready' => 0,
            'current-jobs-reserved' => 5000,
            'current-jobs-delayed' => 0,
            'current-jobs-buried' => 0,
            'cmd-put' => 104,
            'cmd-peek' => 0,
            'cmd-peek-ready' => 0,
            'cmd-peek-delayed' => 0,
            'cmd-peek-buried' => 0,
            'cmd-reserve' => 279066,
            'cmd-reserve-with-timeout' => 0,
            'cmd-delete' => 104,
            'cmd-release' => 0,
            'cmd-use' => 0,
            'cmd-watch' => 2,
            'cmd-ignore' => 4,
            'cmd-bury' => 0,
            'cmd-kick' => 0,
            'cmd-touch' => 0,
            'cmd-stats' => 16,
            'cmd-stats-job' => 0,
            'cmd-stats-tube' => 0,
            'cmd-list-tubes' => 0,
            'cmd-list-tube-used' => 0,
            'cmd-list-tubes-watched' => 0,
            'cmd-pause-tube' => 0,
            'job-timeouts' => 203,
            'total-jobs' => 104,
            'max-job-size' => 65535,
            'current-tubes' => 1,
            'current-connections' => 1,
            'current-producers' => 0,
            'current-workers' => 0,
            'current-waiting' => 0,
            'total-connections' => 130,
            'pid' => 495,
            'version' => '1.10',
            'rusage-utime' => 1.998573,
            'rusage-stime' => 5.843801,
            'uptime' => 2160849,
            'binlog-oldest-index' => 0,
            'binlog-current-index' => 0,
            'binlog-records-migrated' => 0,
            'binlog-records-written' => 0,
            'binlog-max-size' => 10485760,
            'id' => '833874c0b05b8bb5',
            'hostname' => 'sprout-test',
        ];
    }
}
