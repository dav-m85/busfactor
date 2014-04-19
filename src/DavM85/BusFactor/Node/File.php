<?php

namespace DavM85\BusFactor\Node;
use DavM85\BusFactor\Node;

/**
 * Represents a file in the code coverage information tree.
 * @link       http://github.com/sebastianbergmann/php-code-coverage
 */
class File extends Node
{
    private $value;

    /**
     * Constructor.
     *
     * @param  string                       $name
     * @param  PHP_CodeCoverage_Report_Node $parent
     * @param  array                        $coverageData
     * @param  array                        $testData
     * @param  boolean                      $cacheTokens
     * @throws PHP_CodeCoverage_Exception
     */
    public function __construct($name, $value, Node $parent)
    {
        $this->value = $value;
        parent::__construct($name, $parent);
    }

    public function getValue()
    {
        return $this->value;
    }

    /**
     * Returns the number of files in/under this node.
     *
     * @return integer
     */
    public function count()
    {
        return 1;
    }
}
