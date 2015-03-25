<?php

namespace TreeHouse\EntityMerger;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\PersistentCollection;
use Doctrine\ORM\Proxy\Proxy;
use Doctrine\ORM\UnitOfWork;
use JMS\Serializer\Exclusion\ExclusionStrategyInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Metadata\MetadataFactory;
use Symfony\Bridge\Doctrine\ManagerRegistry as Doctrine;

class EntityMerger
{
    /**
     * @var Doctrine
     */
    protected $doctrine;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @var MetadataFactory
     */
    protected $metadataFactory;

    /**
     * @param SerializerInterface $serializer
     * @param Doctrine            $doctrine
     */
    public function __construct(SerializerInterface $serializer, Doctrine $doctrine, MetadataFactory $metadataFactory)
    {
        $this->serializer      = $serializer;
        $this->doctrine        = $doctrine;
        $this->metadataFactory = $metadataFactory;
    }

    protected function doMerge($original, $update, SerializationContext $context = null, ExclusionStrategyInterface $exclusionStrategy = null, $mergeNullValues = false)
    {
        /** @var EntityManager $em */
        $em = $this->doctrine->getManager();
        $uow = $em->getUnitOfWork();

        $oid = spl_object_hash($original);

        $class = $em->getClassMetadata(get_class($original));

        $classMeta = $this->metadataFactory->getMetadataForClass(get_class($original));

        // Merge state of $entity into existing (managed) entity
        foreach ($class->reflClass->getProperties() as $prop) {
            // see if it should be skipped according to the specified group(s)
            $metaProp = $classMeta->propertyMetadata[$prop->getName()];
            if ($exclusionStrategy && $context && $exclusionStrategy->shouldSkipProperty($metaProp, $context)) {
                continue;
            }

            $name = $prop->name;
            $prop->setAccessible(true);
            $value = $prop->getValue($update);

            if (false === $mergeNullValues && is_null($value)) {
                continue;
            }

            if ( ! isset($class->associationMappings[$name])) {
                if ( ! $class->isIdentifier($name)) {
                    $prop->setValue($original, $value);
                }
            } else {
                $assoc2 = $class->associationMappings[$name];
                if ($assoc2['type'] & ClassMetadata::TO_ONE) {
                    $other = $value;
                    if ($other === null) {
                        $prop->setValue($original, null);
                    } elseif ($other instanceof Proxy && !$other->__isInitialized__) {
                        // do not merge fields marked lazy that have not been fetched.
                        continue;
                    } elseif (! $assoc2['isCascadeMerge']) {
                        if ($uow->getEntityState($other) === UnitOfWork::STATE_DETACHED) {
                            $targetClass = $em->getClassMetadata($assoc2['targetEntity']);
                            $relatedId = $targetClass->getIdentifierValues($other);

                            if ($targetClass->subClasses) {
                                $other = $em->find($targetClass->name, $relatedId);
                            } else {
                                $other = $em->getProxyFactory()->getProxy($assoc2['targetEntity'], $relatedId);
                                $uow->registerManaged($other, $relatedId, array());
                            }
                        }

                        $prop->setValue($original, $other);
                    }
                } else {
                    $mergeCol = $value;

                    $managedCol = $prop->getValue($original);
                    $managedColWasEmtpy = false;
                    if (!$managedCol) {
                        $managedCol = new PersistentCollection($em,
                            $em->getClassMetadata($assoc2['targetEntity']),
                            new ArrayCollection
                        );
                        $managedCol->setOwner($original, $assoc2);
                        $prop->setValue($original, $managedCol);
                        $uow->setOriginalEntityProperty($oid, $name, $managedCol);

                        $managedColWasEmtpy = true;
                    } else {
                        // cleanup items no longer in the source. We do it this way instead of a
                        // $collection->clear() and re-adding, because that would trigger
                        // an orphanRemoval and cause the item to be removed
                        foreach ($managedCol as $item) {
                            if (null === $mergeCol || false === $this->inTraversable($item, $mergeCol)) {
                                $managedCol->removeElement($item);
                            }
                        }

                        // now we can clear safely
                        if ($managedCol->count() === 0) {
                            $managedCol->clear();

                            $managedColWasEmtpy = true;
                        }
                    }

                    if ($mergeCol) {
                        foreach ($mergeCol as $subvalue) {
                            // if the collection was empty we can add all, otherwise check that the item isn't already there
                            if ($managedColWasEmtpy || false === $this->inTraversable($subvalue, $managedCol)) {
                                $managedCol->add($subvalue);

                                if (!$assoc2['isOwningSide']) {
                                    $class2 = $em->getClassMetadata($assoc2['targetEntity']);
                                    $prop2 = $class2->reflClass->getProperty($assoc2['mappedBy']);
                                    $prop2->setAccessible(true);

                                    $prop2->setValue($subvalue, $original);
                                }
                            }
                        }
                    }
                }
            }

            if ($class->isChangeTrackingNotify()) {
                // Just treat all properties as changed, there is no other choice.
                $uow->propertyChanged($original, $name, null, $prop->getValue($original));
            }
        }

        if ($class->isChangeTrackingDeferredExplicit()) {
            $uow->scheduleForDirtyCheck($original);
        }

        return $original;
    }

    /**
     * Merges two entities. The entities can be of a different class, as long as property names match, they will be
     * merged.
     *
     * @param object       $original        The original entity to merge into
     * @param object|array $update          The update entity from which values will be merged into the original. Arrays will be deserialized first
     * @param array        $groups          JMS\Serializer groups used for exclusion strategy
     * @param bool         $mergeNullValues Merge values even when they are null
     *
     * @throws \InvalidArgumentException
     *
     * @return object
     */
    public function merge($original, $update, ExclusionStrategyInterface $exclusionStrategy = null, $mergeNullValues = false)
    {
        if (is_array($update)) {
            $update = $this->serializer->deserialize(json_encode($update), get_class($original), 'json');
        }

        if (!is_object($update)) {
            throw new \InvalidArgumentException(
                sprintf('Expecting an object or array for data, got "%s" instead', gettype($update))
            );
        }

        $context = SerializationContext::create();

        return $this->doMerge($original, $update, $context, $exclusionStrategy, $mergeNullValues);
    }

    /**
     * A kind of in_array
     *
     * @param              $needle
     * @param \Traversable $haystack
     *
     * @return bool
     */
    protected function inTraversable($needle, \Traversable $haystack)
    {
        foreach ($haystack as $item) {
            if ($item === $needle) {
                return true;
            }
        }

        return false;
    }
}
