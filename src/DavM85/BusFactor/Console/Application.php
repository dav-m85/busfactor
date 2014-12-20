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
        $client = null;

        // Add the commands
        $commands = array(
            'DavM85\BusFactor\Command\GenerateCommand'
        );
        $app = $this;
        array_walk($commands, function ($commandName) use ($app, $client) {
                $command = new $commandName();
                $app->add($command);
            });
    }
}
