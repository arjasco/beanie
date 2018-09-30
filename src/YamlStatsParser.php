<?php

namespace Arjasco\Sprout;

class YamlStatsParser
{
    /**
     * Parse the YAML stats dictionary to an array.
     *
     * @param string $yaml
     * @return array
     */
    public static function parseDictionary($yaml)
    {
        preg_match_all('/([\w-]+): (.+)/', $yaml, $matches);

        $output = array_combine($matches[1], $matches[2]);

        $casts = [
            'version' => 'string',
            'rusage-utime' => 'float',
            'rusage-stime' => 'float',
            'id' => 'string',
            'hostname' => 'string',
        ];

        foreach ($output as $key => &$value) {
            $cast = $casts[$key] ?? null;

            switch ($cast) {
                case 'float':
                    $value = (float) $value;
                    break;

                case 'string':
                    $value = (string) $value;
                    break;

                default:
                    $value = (int) $value;
            }
        }

        return $output;
    }

    /**
     * Parse a YAML list to an array.
     *
     * @param string $yaml
     * @return array
     */
    public static function parseList($yaml)
    {
        preg_match_all('/- ([\w-]+)/', $yaml, $matches);

        return $matches[1];
    }
}
