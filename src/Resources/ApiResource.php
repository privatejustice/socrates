<?php

namespace Socrates\Resources;

use ReflectionObject;
use ReflectionProperty;

class ApiResource
{
    /** @var array */
    public $attributes = [];

    /** @var \Socrates\Socrates */
    protected $socrates;

    /**
     * @param  array $attributes
     * @param  \Socrates\Socrates $socrates
     */
    public function __construct(array $attributes, $socrates = null)
    {
        $this->attributes = $attributes;

        $this->socrates = $socrates;

        $this->fill();
    }

    protected function fill()
    {
        foreach ($this->attributes as $key => $value) {
            $key = $this->camelCase($key);

            $this->{$key} = $value;
        }
    }

    protected function camelCase(string $key): string
    {
        $parts = explode('_', $key);

        foreach ($parts as $i => $part) {
            if ($i !== 0) {
                $parts[$i] = ucfirst($part);
            }
        }

        return str_replace(' ', '', implode(' ', $parts));
    }

    public function __sleep()
    {
        $publicProperties = (new ReflectionObject($this))->getProperties(ReflectionProperty::IS_PUBLIC);

        $publicPropertyNames = array_map(function (ReflectionProperty $property) {
            return $property->getName();
        }, $publicProperties);

        return array_diff($publicPropertyNames, ['socrates', 'attributes']);
    }
}
