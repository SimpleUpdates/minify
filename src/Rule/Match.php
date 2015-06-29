<?php
namespace MatthiasMullie\Minify\Rule;

/**
 * Represents a Rule match in a simple value object.
 *
 * I'm using __get to expose the data as simple properties,
 * but making them inaccessible for modification (no __set)
 *
 * @property int $offset
 * @property int $length
 * @property mixed $data
 */
class Match
{
    /**
     * @var int
     */
    protected $offset;

    /**
     * @var int
     */
    protected $length;

    /**
     * @var mixed
     */
    protected $data;

    /**
     * @param int $offset
     * @param int $length
     * @param mixed $data
     */
    public function __construct($offset, $length, $data = null)
    {
        $this->offset = $offset;
        $this->length = $length;
        $this->data = $data;
    }

    /**
     * @param string $property
     * @return mixed
     * @throws Exception
     */
    public function __get($property)
    {
        if (!property_exists($this, $property)) {
            throw new Exception('Requested non-existing property: '.$property);
        }

        return $this->$property;
    }
}
