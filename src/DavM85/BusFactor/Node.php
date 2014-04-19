<?php

namespace DavM85\BusFactor;

/**
 * Base class for nodes in the code coverage information tree.
 * @link       http://github.com/sebastianbergmann/php-code-coverage
 */
abstract class Node implements \Countable
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var array
     */
    protected $pathArray;

    /**
     * @var Node
     */
    protected $parent;

    /**
     * @var string
     */
    protected $id;

    /**
     * Constructor.
     *
     * @param string                       $name
     * @param Node $parent
     */
    public function __construct($name, Node $parent = null)
    {
        if (substr($name, -1) == '/') {
            $name = substr($name, 0, -1);
        }
        $this->name   = $name;
        $this->parent = $parent;
    }

    /**
     * @return string
     */
    public function getName()
    {

        return $this->name;
    }

    /**
     * @return string
     */
    public function getId()
    {
        if ($this->id === null) {
            $parent = $this->getParent();

            if ($parent === null) {
                $this->id = 'index';
            } else {
                $parentId = $parent->getId();

                if ($parentId == 'index') {
                    $this->id = str_replace(':', '_', $this->name);
                } else {
                    $this->id = $parentId . '/' . $this->name;
                }
            }
        }

        return $this->id;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        if ($this->path === null) {
            if ($this->parent === null || $this->parent->getPath() === null) {
                $this->path = $this->name;
            } else {
                $this->path = $this->parent->getPath() . '/' . $this->name;
            }
        }

        return $this->path;
    }

    /**
     * @return array
     */
    public function getPathAsArray()
    {
        if ($this->pathArray === null) {
            if ($this->parent === null) {
                $this->pathArray = array();
            } else {
                $this->pathArray = $this->parent->getPathAsArray();
            }

            $this->pathArray[] = $this;
        }

        return $this->pathArray;
    }

    /**
     * @return Node
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Return the stats
     * @return mixed
     */
    abstract public function getValue();
}
