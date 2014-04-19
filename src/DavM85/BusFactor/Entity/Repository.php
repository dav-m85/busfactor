<?php

namespace DavM85\BusFactor\Entity;

use Symfony\Component\Console\Output\OutputInterface;
use DavM85\BusFactor\Entity\FileCommitPoint;
use DavM85\BusFactor\Entity\PathHistory;

class Repository
{
    public $path;
    public $origin;
    public $name;

    public function exec($command)
    {
        $command = sprintf('git --git-dir %s %s',
            $this->path,
            $command
        );

        // echo $command . PHP_EOL;

        exec($command, $output, $return_var);

        if ($return_var !== 0) {
            // var_dump($command, $output, $return_var);
            throw new \Exception('do not work');
        }

        return $output;
    }

    public function execNoGitdir($command)
    {
        $command = sprintf('git %s',
            $command
        );

        exec($command, $output, $return_var);

        if ($return_var !== 0) {
            var_dump($command, $output, $return_var);
            throw new \Exception('do not work');
        }

        return $output;
    }

    public function listFiles()
    {
        return $this->exec('ls-files');
    }

    // this is painfully slow on big repos...
    // shall find something more clever to do.
    public function getStatisticForPath($path)
    {
        $data = $this->exec(sprintf('log --no-color --max-count=1000 --pretty="tformat:%s" --name-only', Commit::$format));

        if(is_string($data)){$data = array($data);}
        if(is_null($data)){$data=array();}
        $commits = Commit::parse($data);
        return $commits;
    }
}
