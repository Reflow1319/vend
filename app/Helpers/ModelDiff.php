<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;

class ModelDiff
{
    /**
     * @var Model
     */
    private $from;

    /**
     * @var Model
     */
    private $to;

    /**
     * @var array
     */
    private $properties;

    /**
     * @var array
     */
    private $mappings = [];

    /**
     * ModelDiff constructor.
     *
     * @param Model $from
     * @param Model $to
     * @param array $properties
     */
    public function __construct(Model $from, Model $to, $properties = [])
    {
        $this->from = $from;
        $this->to = $to;
        $this->properties = $properties;
    }

    /**
     * Add a map.
     *
     * @param $property
     * @param $model
     * @param $field
     */
    public function map($property, $model, $field)
    {
        $this->mappings[$property] = [$model, $field];
    }

    /**
     * Returns the model differences.
     *
     * @return array
     */
    public function get()
    {
        $changes = [];

        foreach ($this->properties as $property) {
            if ($this->from->{$property} !== $this->to->{$property}) {
                $changes[] = [
                    'property' => $property,
                    'from'     => $this->getMapping($this->from, $property),
                    'to'       => $this->getMapping($this->to, $property),
                ];
            }
        }

        return $changes;
    }

    /**
     * Checks for property mappings.
     *
     * Mapped properties returns the related model field
     * instead of the original value
     *
     * @param $model
     * @param $property
     *
     * @return array
     */
    private function getMapping($model, $property)
    {
        if (array_key_exists($property, $this->mappings)) {
            $resource = $this->mappings[$property][0];
            $field = $this->mappings[$property][1];

            return (new $resource())->find($model->{$property})->{$field};
        } else {
            return $model->{$property};
        }
    }
}
