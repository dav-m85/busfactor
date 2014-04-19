<?php

namespace DavM85\BusFactor\Node;

use DavM85\BusFactor\Entity\PathStatistic;
use DavM85\BusFactor\Node\File;
use DavM85\BusFactor\Node;

/**
 * Represents a directory in the tree.
 * @link       http://github.com/sebastianbergmann/php-code-coverage
 */
class Directory extends Node implements \IteratorAggregate
{
    /**
     * @var Node[]
     */
    protected $children = array();

    /**
     * @var Directory[]
     */
    protected $directories = array();

    /**
     * @var File[]
     */
    protected $files = array();

    /**
     * Returns the number of files in/under this node.
     *
     * @return integer
     */
    public function count()
    {
        if ($this->numFiles == -1) {
            $this->numFiles = 0;

            foreach ($this->children as $child) {
                $this->numFiles += count($child);
            }
        }

        return $this->numFiles;
    }

    /**
     * Returns an iterator for this node.
     *
     * @return RecursiveIteratorIterator
     */
    public function getIterator()
    {
        return new \RecursiveIteratorIterator(
            new Iterator($this),
            \RecursiveIteratorIterator::SELF_FIRST
        );
    }

    /**
     * Adds a new directory.
     *
     * @param  string                                 $name
     * @return PHP_CodeCoverage_Report_Node_Directory
     */
    public function addDirectory($name)
    {
        $directory = new self($name, $this);

        $this->children[]    = $directory;
        $this->directories[] = &$this->children[count($this->children) - 1];

        return $directory;
    }

    /**
     * Adds a new file.
     *
     * @param  string                            $name
     * @param  array                             $coverageData
     * @param  array                             $testData
     * @param  boolean                           $cacheTokens
     * @return PHP_CodeCoverage_Report_Node_File
     * @throws PHP_CodeCoverage_Exception
     */
    public function addFile($name, $value)
    {
        $file = new File(
            $name, $value, $this
        );

        $this->children[] = $file;
        $this->files[]    = &$this->children[count($this->children) - 1];

        return $file;
    }

    /**
     * Returns the directories in this directory.
     *
     * @return array
     */
    public function getDirectories()
    {
        return $this->directories;
    }

    /**
     * Returns the files in this directory.
     *
     * @return array
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Returns the child nodes of this node.
     *
     * @return array
     */
    public function getChildNodes()
    {
        return $this->children;
    }

    public function removeAllChilds()
    {
        $this->children = array();
        $this->directories = array();
    }

    /**
     * Return the stats
     * @return mixed
     */
    public function getValue()
    {
        $stat = new PathStatistic(array());
        $stats = array();
        foreach($this as $node){
            if(! $node instanceof File){
                continue;
            }
            $stats[] = $node->getValue();
        }
        return $stat->aggregate($stats);
    }
}
