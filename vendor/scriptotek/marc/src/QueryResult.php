<?php

namespace Scriptotek\Marc;

use ArrayAccess;
use ArrayIterator;
use Countable;
use File_MARC_Field;
use File_MARC_Reference;
use IteratorAggregate;
use Traversable;
use Scriptotek\Marc\Fields\Field;

class QueryResult implements IteratorAggregate, ArrayAccess, Countable
{
    protected $ref;
    protected $data;
    protected $content;

    /**
     * QueryResult constructor.
     *
     * @param File_MARC_Reference $ref
     */
    public function __construct(File_MARC_Reference $ref)
    {
        $this->ref = $ref->ref;
        $this->data = $ref->data;
        $this->content = $ref->content;

        for ($i=0; $i < count($this->data); $i++) {
            if (is_a($this->data[$i], File_MARC_Field::class)) {
                $this->data[$i] = new Field($this->data[$i]);
            }
        }
    }

    public function getReference()
    {
        return $this->ref;
    }

    /**
     * Get the first result (field or subfield), or null if no results.
     *
     * @return File_MARC_Field|File_MARC_Subfield|null
     */
    public function first()
    {
        return isset($this->data[0]) ? $this->data[0] : null;
    }

    /**
     * Get the text content of the first result, or null if no results.
     *
     * @return string
     */
    public function text()
    {
        return isset($this->content[0]) ? $this->content[0] : null;
    }

    /**
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable
     */
    public function getIterator()
    {
        return new ArrayIterator($this->data);
    }

    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset An offset to check for.
     * @return boolean true on success or false on failure.
     */
    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset The offset to retrieve.
     * @return File_MARC_Field|File_MARC_Subfield
     */
    public function offsetGet($offset)
    {
        return $this->data[$offset];
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset The offset to assign the value to.
     * @param mixed $value The value to set.
     */
    public function offsetSet($offset, $value)
    {
        $this->data[$offset] = $value;
    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset The offset to unset.
     */
    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    /**
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The number of results
     */
    public function count()
    {
        return count($this->data);
    }
}
