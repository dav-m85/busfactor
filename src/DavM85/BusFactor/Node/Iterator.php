<?php

namespace DavM85\BusFactor\Node;

/**
 * Recursive iterator for PHP_CodeCoverage_Report_Node object graphs.
 * @link       http://github.com/sebastianbergmann/php-code-coverage
 */
class Iterator implements \RecursiveIterator
{
    /**
     * @var integer
     */
    protected $position;

    /**
     * @var Node[]
     */
    protected $nodes;

    /**
     * Constructor.
     *
     * @param Directory $node
     */
    public function __construct(Directory $node)
    {
        $this->nodes = $node->getChildNodes();
    }

    /**
     * Rewinds the Iterator to the first element.
     *
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * Checks if there is a current element after calls to rewind() or next().
     *
     * @return boolean
     */
    public function valid()
    {
        return $this->position < count($this->nodes);
    }

    /**
     * Returns the key of the current element.
     *
     * @return integer
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * Returns the current element.
     *
     * @return PHPUnit_Framework_Test
     */
    public function current()
    {
        return $this->valid() ? $this->nodes[$this->position] : null;
    }

    /**
     * Moves forward to next element.
     *
     */
    public function next()
    {
        $this->position++;
    }

    /**
     * Returns the sub iterator for the current element.
     *
     * @return PHP_CodeCoverage_Report_Node_Iterator
     */
    public function getChildren()
    {
        return new self(
            $this->nodes[$this->position]
        );
    }

    /**
     * Checks whether the current element has children.
     *
     * @return boolean
     */
    public function hasChildren()
    {
        return $this->nodes[$this->position] instanceof Directory;
    }
}
