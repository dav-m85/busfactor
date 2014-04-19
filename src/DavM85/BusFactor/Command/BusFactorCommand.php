<?php

namespace DavM85\BusFactor\Command;

use DavM85\BusFactor\Entity\PathStatistic;
use DavM85\BusFactor\Report\Factory;
use DavM85\BusFactor\Report\HTML;
use DavM85\BusFactor\RepositoryManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use DavM85\BusFactor\Entity\Repository;
use DavM85\BusFactor\Entity\Commit;

class BusFactorCommand extends AbstractCommand
{
    protected function configure()
    {
        $this
            ->setName('build')
            ->addArgument('path', InputArgument::REQUIRED, 'Path to a git repository folder')
            ->addOption('output', 'o', InputOption::VALUE_REQUIRED, 'Path to the output dir. Default ./out')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        date_default_timezone_set('UTC');

        $configuration = $this->getConfiguration();
        $hasOutput = $input->hasOption('output');
        $targetDir = $hasOutput ? $input->getOption('output') : $configuration['rootPath'] . '/out';
        $gitDir = $input->getArgument('path');

        // Get a repository
        $repository = new Repository();
        $repository->path = $gitDir . DIRECTORY_SEPARATOR . '.git';

        // List all current files
        $data = array();
        $files = $repository->listFiles();

        $output->writeln('Found <info>'.count($files).'</info> files.');
        foreach($files as $f){
            $full = $gitDir . DIRECTORY_SEPARATOR . $f;
            $data[$full] = new PathStatistic();
        }

        // Get full history and pour it inside stats
        $commits = $repository->getStatisticForPath('.');

        foreach($commits as $commit){
            /** @var Commit $commit */
            foreach($commit->paths as $path){
                $key = $gitDir . DIRECTORY_SEPARATOR .$path;
                if(array_key_exists($key, $data)){
                    $data[$key]->addCommit($commit);
                }
            }
        }

        // Create the node structure
        $factory = new Factory();
        $rootNode = $factory->create($data);

        // Generate the output
        $html = new HTML($this->getTwig($configuration));
        $html->process($rootNode, $targetDir);
    }

    public function getTwig($configuration)
    {
        $templateDir = $configuration['rootPath'] . '/src/DavM85/BusFactor/Resources/views';

        $loader = new \Twig_Loader_Filesystem($templateDir);
        $twig = new \Twig_Environment($loader, array());
        $twig->addGlobal('rootPath', 'file:///Users/david/Projects/BusFactor/out/');

        $common = array(
            'id'               => '', //$node->getId(),
            'full_path'        => '', //$node->getPath(),
            'path_to_root'     => '', //$this->getPathToRoot($node),
            'breadcrumbs'      => '', //$this->getBreadcrumbs($node),
            'date'             => '', //$this->date,
            'version'          => '', //$this->version,
            'runtime_name'     => '', //$runtime->getName(),
            'runtime_version'  => '', //$runtime->getVersion(),
            'runtime_link'     => '', //$runtime->getVendorUrl(),
            'generator'        => '', //$this->generator,
            'low_upper_bound'  => '', //$this->lowUpperBound,
            'high_lower_bound' => '', //$this->highLowerBound
        );

        return $twig;
    }
}
