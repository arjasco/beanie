<?php

namespace Arjasco\Beanie;

class YamlStatsParser
{
    /**
     * Parse YAML stats dictionary
     *
     * @param string $yaml
     * @return array
     */
    public static function parseDictionary($yaml)
    {
        preg_match_all('/([\w-]+): (.+)/', $yaml, $matches);

        return array_combine($matches[1], $matches[2]);
    }
}
