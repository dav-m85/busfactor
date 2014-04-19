<?php

namespace DavM85\BusFactor\Report;
use DavM85\BusFactor\Node\Directory;
use DavM85\BusFactor\Report\HTML\Renderer\Dashboard;
use DavM85\BusFactor\Report\HTML\Renderer\File;

/**
 * Generates an HTML report from an PHP_CodeCoverage object.
 * @link       http://github.com/sebastianbergmann/php-code-coverage
 */
class HTML
{
    private $twig;

    /**
     * Constructor.
     *
     * @param integer $lowUpperBound
     * @param integer $highLowerBound
     * @param string  $generator
     */
    public function __construct(\Twig_Environment $twig) // gen ?
    {
        $this->twig      = $twig;
    }

    /**
     * @param PHP_CodeCoverage $coverage
     * @param string           $target
     */
    public function process(Directory $rootNode, $target)
    {
        $target = $this->getDirectory($target);

        if (!isset($_SERVER['REQUEST_TIME'])) {
            $_SERVER['REQUEST_TIME'] = time();
        }

        $date = 'D M j G:i:s T Y' ;//date('D M j G:i:s T Y', $_SERVER['REQUEST_TIME']);
        /*
        $dashboard = new Dashboard(
            $this->templatePath,
            $this->generator,
            $date
        );
        */
        $directory = new \DavM85\BusFactor\Report\HTML\Renderer\Directory(
            $this->twig,
            $date
        );
        /*
        $file = new File(
            $this->templatePath,
            $this->generator,
            $date
        );
        */

        $directory->render($rootNode, $target . 'index.html');

        $this->copyFiles($target);

        // $dashboard->render($rootNode, $target . 'dashboard.html');

        foreach ($rootNode as $node) {
            $id = $node->getId();

            if ($node instanceof Directory) {
                if (!file_exists($target . $id)) {
                    mkdir($target . $id, 0777, true);
                }

                $directory->render($node, $target . $id . '/index.html');
                // $dashboard->render($node, $target . $id . '/dashboard.html');
            } else {
                // remove !!!
                continue;
                $dir = dirname($target . $id);

                if (!file_exists($dir)) {
                    mkdir($dir, 0777, true);
                }

                $file->render($node, $target . $id . '.html');
            }
        }

        //
    }

    /**
     * @param string $target
     */
    private function copyFiles($target)
    {
        $assetsPath = realpath(__DIR__ . '/../Resources/assets') . '/';

        $dir = $this->getDirectory($target . 'css');
        copy($assetsPath . 'css/bootstrap.min.css', $dir . 'bootstrap.min.css');
        copy($assetsPath . 'css/nv.d3.css', $dir . 'nv.d3.css');
        copy($assetsPath . 'css/style.css', $dir . 'style.css');

        $dir = $this->getDirectory($target . 'fonts');
        copy($assetsPath . 'fonts/glyphicons-halflings-regular.eot', $dir . 'glyphicons-halflings-regular.eot');
        copy($assetsPath . 'fonts/glyphicons-halflings-regular.svg', $dir . 'glyphicons-halflings-regular.svg');
        copy($assetsPath . 'fonts/glyphicons-halflings-regular.ttf', $dir . 'glyphicons-halflings-regular.ttf');
        copy($assetsPath . 'fonts/glyphicons-halflings-regular.woff', $dir . 'glyphicons-halflings-regular.woff');

        $dir = $this->getDirectory($target . 'js');
        copy($assetsPath . 'js/bootstrap.min.js', $dir . 'bootstrap.min.js');
        copy($assetsPath . 'js/d3.min.js', $dir . 'd3.min.js');
        copy($assetsPath . 'js/holder.js', $dir . 'holder.js');
        copy($assetsPath . 'js/html5shiv.js', $dir . 'html5shiv.js');
        copy($assetsPath . 'js/jquery.js', $dir . 'jquery.js');
        copy($assetsPath . 'js/nv.d3.min.js', $dir . 'nv.d3.min.js');
        copy($assetsPath . 'js/respond.min.js', $dir . 'respond.min.js');
    }

    /**
     * @param  string                     $directory
     * @return string
     * @throws PHP_CodeCoverage_Exception
     * @since  Method available since Release 1.2.0
     */
    private function getDirectory($directory)
    {
        if (substr($directory, -1, 1) != DIRECTORY_SEPARATOR) {
            $directory .= DIRECTORY_SEPARATOR;
        }

        if (is_dir($directory)) {
            return $directory;
        }

        if (@mkdir($directory, 0777, true)) {
            return $directory;
        }

        throw new PHP_CodeCoverage_Exception(
            sprintf(
                'Directory "%s" does not exist.',
                $directory
            )
        );
    }
}
