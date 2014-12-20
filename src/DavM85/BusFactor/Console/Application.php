<?php

namespace DavM85\BusFactor\Console;

use Symfony\Component\Console\Application as BaseApplication;
use DavM85\BusFactor\Factory\ConfigurationFactory;

class Application extends BaseApplication
{
    public function __construct()
    {
        // Create the app
        parent::__construct('git busfactor', APP_VERSION);

        // Create some services
        $configurationFactory = new ConfigurationFactory();
        $configuration = $configurationFactory->make('config.yml');
        $client = null;

        // Add the commands
        $commands = array(
            'DavM85\BusFactor\Command\BusFactorCommand'
        );
        $app = $this;
        array_walk($commands, function ($commandName) use ($app, $configuration, $client) {
                $command = new $commandName();
                $command->setConfiguration($configuration);
                $app->add($command);
            });
    }
}
