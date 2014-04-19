<?php

namespace DavM85\BusFactor\Report\HTML\Renderer;
use DavM85\BusFactor\Node;
use DavM85\BusFactor\Report\HTML\Renderer;

/**
 * Renders a PHP_CodeCoverage_Report_Node_Directory node.
 * @link       http://github.com/sebastianbergmann/php-code-coverage
 */
class Directory extends Renderer
{
    /**
     * @param PHP_CodeCoverage_Report_Node_Directory $node
     * @param string                                 $file
     */
    public function render(\DavM85\BusFactor\Node\Directory $node, $file)
    {
        // $this->setCommonTemplateVariables($template, $node);
        $items = array();
        // $items[] = $this->renderItem($node, true);

        foreach ($node->getDirectories() as $item) {
            $items[] = $this->renderItem($item);
        }

        foreach ($node->getFiles() as $item) {
            $items[] = $this->renderItem($item);
        }

        $data = array(
                'id'    => $node->getId(),
                'items' => $items,
                'breadcrumbs' => $this->getBreadcrumbs($node)
        );
        $html = $this->twig->render('directory.htm.twig', $data);
        file_put_contents($file, $html);
    }

    /**
     * @param  PHP_CodeCoverage_Report_Node $item
     * @param  boolean                      $total
     * @return string
     */
    protected function renderItem(Node $item, $total = false)
    {
        if ($total) {
            $data['name'] = 'Total';
        } else {
            if ($item instanceof \DavM85\BusFactor\Node\Directory) {

                $data['name'] = sprintf(
                    '<a href="%s/index.html">%s</a>',
                    $item->getName(),
                    $item->getName()
                );

                $data['icon'] = '<span class="glyphicon glyphicon-folder-open"></span> ';

            } else {
                // Files got no name
                $data['name'] = $item->getName();
                $data['icon'] = '<span class="glyphicon glyphicon-file"></span> ';
            }

            $data = array_merge($data, $item->getValue()->getData());
        }

        return $data;
    }


}
