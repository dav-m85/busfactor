<?php

namespace DavM85\BusFactor\Report\HTML;
use DavM85\BusFactor\Node;

/**
 * Base class for PHP_CodeCoverage_Report_Node renderers.
 * @link       http://github.com/sebastianbergmann/php-code-coverage
 */
abstract class Renderer
{
    /**
     * @var string
     */
    protected $twig;

    /**
     * @var string
     */
    protected $date;

    /**
     * Constructor.
     *
     * @param string  $templatePath
     * @param string  $generator
     * @param string  $date
     * @param integer $lowUpperBound
     * @param integer $highLowerBound
     */
    public function __construct(\Twig_Environment $twig, $date)
    {
        $this->twig = $twig;
        $this->date = $date;
    }

    /**
     * @param  float $percent
     * @return string
     */
    protected function getCoverageBar($percent)
    {
        $level = $this->getColorLevel($percent);
        return $this->twig->render('coverage_bar.html.twig', array('level' => $level, 'percent' => sprintf("%.2F", $percent)));
    }

    /**
     * @param Text_Template                $template
     * @param PHP_CodeCoverage_Report_Node $node
     */
    protected function setCommonTemplateVariables(Text_Template $template, PHP_CodeCoverage_Report_Node $node)
    {
        $runtime = new Runtime;

        $template->setVar(
            array(
                'id'               => $node->getId(),
                'full_path'        => $node->getPath(),
                'path_to_root'     => $this->getPathToRoot($node),
                'breadcrumbs'      => $this->getBreadcrumbs($node),
                'date'             => $this->date,
                'version'          => $this->version,
                'runtime_name'     => $runtime->getName(),
                'runtime_version'  => $runtime->getVersion(),
                'runtime_link'     => $runtime->getVendorUrl(),
                'generator'        => $this->generator,
                'low_upper_bound'  => $this->lowUpperBound,
                'high_lower_bound' => $this->highLowerBound
            )
        );
    }

    /**
     * @param  PHP_CodeCoverage_Report_Node $node
     * @return string
     */
    protected function getBreadcrumbs(Node $node)
    {
        $breadcrumbs = '';
        $path        = $node->getPathAsArray();
        $pathToRoot  = array();
        $max         = count($path);

        if ($node instanceof Node\File) {
            $max--;
        }

        for ($i = 0; $i < $max; $i++) {
            $pathToRoot[] = str_repeat('../', $i);
        }

        foreach ($path as $step) {
            if ($step !== $node) {
                $breadcrumbs .= $this->getInactiveBreadcrumb(
                    $step, array_pop($pathToRoot)
                );
            } else {
                $breadcrumbs .= $this->getActiveBreadcrumb($step);
            }
        }

        return $breadcrumbs;
    }

    /**
     * @param  PHP_CodeCoverage_Report_Node $node
     * @return string
     */
    protected function getActiveBreadcrumb(Node $node)
    {
        $buffer = sprintf(
            '        <li class="active">%s</li>' . "\n",
            $node->getName()
        );

        if ($node instanceof Node\Directory) {
            $buffer .= '        <li>(<a href="dashboard.html">Dashboard</a>)</li>' . "\n";
        }

        return $buffer;
    }

    /**
     * @param  PHP_CodeCoverage_Report_Node $node
     * @param  $pathToRoot
     * @return string
     */
    protected function getInactiveBreadcrumb(Node $node, $pathToRoot)
    {
        return sprintf(
            '        <li><a href="%sindex.html">%s</a></li>' . "\n",
            $pathToRoot,
            $node->getName()
        );
    }

    /**
     * @param  PHP_CodeCoverage_Report_Node $node
     * @return string
     */
    protected function getPathToRoot(Node $node)
    {
        $id    = $node->getId();
        $depth = substr_count($id, '/');

        if ($id != 'index' &&
            $node instanceof Node\Directory) {
            $depth++;
        }

        return str_repeat('../', $depth);
    }
}
