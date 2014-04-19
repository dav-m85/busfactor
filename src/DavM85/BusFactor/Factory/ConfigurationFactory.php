<?php

namespace DavM85\BusFactor\Factory;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Config\Definition\Processor;
use DavM85\BusFactor\Entity\Configuration;

class ConfigurationFactory
{
    public function make($relativePath)
    {
        $path = __DIR__.'/../../../../' . $relativePath;
        if (! file_exists($path)) {
            throw new \Exception("No configuration file. Please create $relativePath !");
        }
        $config = Yaml::parse($path);
        $configs = array($config);

        $processor = new Processor();
        $processedConfiguration = $processor->processConfiguration(
            new Configuration,
            $configs)
        ;

        $processedConfiguration['rootPath'] = $this->getRootPath();

        return $processedConfiguration;
    }

    private function getRootPath()
    {
        return dirname(realpath($_SERVER['argv'][0]));
    }
}
