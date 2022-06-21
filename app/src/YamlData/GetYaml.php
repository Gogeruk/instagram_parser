<?php

namespace App\YamlData;

use Symfony\Component\Yaml\Yaml;

/**
 * GetYaml
 */
class GetYaml
{
    /**
     * @param string $path
     * @return mixed
     */
    public function getArrayFromYaml(string $path)
    {
        return Yaml::parseFile($path);
    }
}