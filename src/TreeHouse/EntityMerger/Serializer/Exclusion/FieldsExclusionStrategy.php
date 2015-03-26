<?php

namespace TreeHouse\EntityMerger\Serializer\Exclusion;

use JMS\Serializer\Exclusion\ExclusionStrategyInterface;
use JMS\Serializer\Metadata\ClassMetadata;
use JMS\Serializer\Metadata\PropertyMetadata;
use JMS\Serializer\Context;

class FieldsExclusionStrategy implements ExclusionStrategyInterface
{
    /**
     * @var array exclude all fields that are NOT in this array
     */
    private $fields = array();

    /**
     * @param array $fields
     */
    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    /**
     * {@inheritDoc}
     */
    public function shouldSkipClass(ClassMetadata $metadata, Context $navigatorContext)
    {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function shouldSkipProperty(PropertyMetadata $property, Context $navigatorContext)
    {
        return !in_array($property->name, $this->fields);
    }
}
