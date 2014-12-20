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

class GenerateCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('generate')
            ->addArgument('path', InputArgument::REQUIRED, 'Path to a git repository folder')
            ->addOption('output', 'o', InputOption::VALUE_REQUIRED, 'Path to the output dir.', './out')
            ->addOption('access_token', 'a', InputOption::VALUE_REQUIRED, 'Github access token')
            ->addOption('organisation', null, InputOption::VALUE_REQUIRED, 'Github access token')
            ->addOption('lower_threshold', 'lt', InputOption::VALUE_REQUIRED, 'Github access token', 10)
            ->addOption('higher_threshold', 'ht', InputOption::VALUE_REQUIRED, 'Github access token', 40)
            ->addOption('rootPath', 'r', InputOption::VALUE_REQUIRED, 'Github access token', $this->getRootPath())
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        date_default_timezone_set('UTC');

        $rootPath = $input->getOption('rootPath');
        $targetDir = $rootPath . '/' . $input->getOption('output');
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

        // MEMLEAK HERE !
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
        $html = new HTML($this->getTwig(array(
            'rootPath' => $input->getOption('rootPath'),
            'lower_threshold' => $input->getOption('lower_threshold'),
            'higher_threshold' => $input->getOption('higher_threshold')
        )));
        $html->process($rootNode, $targetDir);
    }

    public function getTwig($configuration)
    {
        $templateDir = $configuration['rootPath'] . '/src/DavM85/BusFactor/Resources/views';

        $loader = new \Twig_Loader_Filesystem($templateDir);
        $twig = new \Twig_Environment($loader, array());

        $twig->addGlobal('rootPath', $configuration['rootPath'] . '/out'); //@todo change that
        $twig->addGlobal('lower', $configuration['lower_threshold']);
        $twig->addGlobal('higher', $configuration['higher_threshold']);

        $filter = new \Twig_SimpleFilter('level', function ($percent) use ($configuration){
            $lower = $configuration['lower_threshold'];
            $higher = $configuration['higher_threshold'];
            if ($percent < $lower) {
                return 'success';
            } elseif ($percent >= $lower &&
                $percent <  $higher) {
                return 'warning';
            } else {
                return 'danger';
            }
        });
        $twig->addFilter($filter);

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

    private function getRootPath()
    {
        return dirname(realpath($_SERVER['argv'][0]));
    }
}
