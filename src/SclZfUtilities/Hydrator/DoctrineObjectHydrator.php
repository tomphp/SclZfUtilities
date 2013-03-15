<?php

namespace SclZfUtilities\Hydrator;

use DateTime;
use Traversable;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Collections\ArrayCollection;
use Zend\Stdlib\ArrayUtils;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

/**
 * Hydrator based on Doctrine ObjectManager. Hydrates an object using a wrapped hydrator and
 * by retrieving associations by the given identifiers.
 * Please note that non-scalar values passed to the hydrator are considered identifiers too.
 *
 * @license MIT
 * @link    http://www.doctrine-project.org/
 * @since   0.5.0
 * @author  Michael Gallego <mic.gallego@gmail.com>
 */
class DoctrineObjectHydrator implements HydratorInterface
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var HydratorInterface
     */
    protected $hydrator;

    protected $metadata;

    /**
     * @param ObjectManager     $objectManager
     * @param HydratorInterface $hydrator
     */
    public function __construct(ObjectManager $objectManager, HydratorInterface $hydrator = null)
    {
        $this->objectManager = $objectManager;

        if (null === $hydrator) {
            $hydrator = new ClassMethodsHydrator(false);
        }

        $this->setHydrator($hydrator);
    }

    /**
     * @param HydratorInterface $hydrator
     * @return DoctrineObject
     */
    public function setHydrator(HydratorInterface $hydrator)
    {
        $this->hydrator = $hydrator;

        return $this;
    }

    /**
     * @return HydratorInterface
     */
    public function getHydrator()
    {
        return $this->hydrator;
    }

    public function isDateOrTime($field)
    {
        return in_array($this->metadata->getTypeOfField($field), array('datetime', 'time', 'date'));
    }

    /**
     * @todo DateTime (and other types) conversion should be handled by doctrine itself in future
     */
    public function formatDateTime($field, $value)
    {
        if (!$this->isDateOrTime($field)) {
            return $value;
        }

        if (!$value instanceof DateTime) {
            return $value;
        }

        return $value->format(DateTime::ISO8601);
    }

    public function getAssociationValue($field, $value)
    {
        if (!$this->metadata->hasAssociation($field)) {
            return $value;
        }

        $value = $this->hydrator->extract($value);

        if ($this->metadata->isSingleValuedAssociation($field)) {
            $value = $value['id'];
        } elseif ($this->metadata->isCollectionValuedAssociation($field)) {
            // @todo wip
        }

        return $value;
    }

    public function extractField($field, $value)
    {
        if ($value === null) {
            return $value;
        }

        $value = $this->formatDateTime($field, $value);

        $value = $this->getAssociationValue($field, $value);

        return $value;
    }

    /**
     * Extract values from an object
     *
     * @param  object $object
     * @return array
     */
    public function extract($object)
    {
        $this->metadata = $this->objectManager->getClassMetadata(get_class($object));
        $data = $this->hydrator->extract($object);

        foreach ($data as $field => &$value) {
            if (!$this->metadata->hasField($field) && !$this->metadata->hasAssociation($field)) {
                unset($data[$field]);
                continue;
            }

            $value = $this->extractField($field, $value);
        }

        return $data;
    }

    /**
     * @todo DateTime (and other types) conversion should be handled by doctrine itself in future
     */
    public function processDateTime($field, $value)
    {
        if (!$this->isDateOrTime($this->metadata, $field)) {
            return $value;
        }

        if (is_int($value)) {
            return new DateTime("@{$value}");
        } elseif (is_string($value)) {
            return new DateTime($value);
        }

        return $value;
    }

    public function makeAssociation($field, $value)
    {
        if (!$this->metadata->hasAssociation($field)) {
            return $value;
        }

        $target = $this->metadata->getAssociationTargetClass($field);

        if ($this->metadata->isSingleValuedAssociation($field)) {
            return $this->toOne($value, $target);
        }

        if ($this->metadata->isCollectionValuedAssociation($field)) {
            return $this->toMany($value, $target);
        }

        return $value;
    }

    public function hydrateField($field, $value)
    {
        if ($value === null) {
            return $value;
        }

        $value = $this->processDateTime($field, $value);

        return $this->makeAssociation($field, $value);
    }


    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array  $data
     * @param  object $object
     * @throws \Exception
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $this->metadata = $this->objectManager->getClassMetadata(get_class($object));

        foreach ($data as $field => &$value) {
            $value = $this->hydrateField($field, $value);
        }

        return $this->hydrator->hydrate($data, $object);
    }

    /**
     * @param mixed  $valueOrObject
     * @param string $target
     * @return object
     */
    protected function toOne($valueOrObject, $target)
    {
        if ($valueOrObject instanceof $target) {
            return $valueOrObject;
        }

        if (is_array($valueOrObject)) {
            // @todo check is there is a way to find what the primary key parameter is really called
            if (isset($valueOrObject['id'])) {
                $object = $this->find($target, $valueOrObject['id']);
            } else {
                $object = new $target;
            }

            $hydrator = new DoctrineObjectHydrator($this->objectManager, $this->hydrator);

            return $hydrator->hydrate($valueOrObject, $object);
        }

        return $this->find($target, $valueOrObject);
    }

    /**
     * @param mixed $valueOrObject
     * @param string $target
     * @return ArrayCollection
     */
    protected function toMany($valueOrObject, $target)
    {
        if (!is_array($valueOrObject) && !$valueOrObject instanceof Traversable) {
            $valueOrObject = array($valueOrObject);
        }

        $collection = new ArrayCollection();

        foreach ($valueOrObject as $value) {
            if ($value instanceof $target) {
                $collection->add($value);
            } else {
                $collection->add($this->find($target, $value));
            }
        }

        return $collection;
    }

    /**
     * @param  mixed  $valueOrObject
     * @param  string $target
     * @param  string $joinColumn
     * @return string
     */
    protected function fromOne($value, $target, $refColumn)
    {
        if (!$value instanceof $target) {
            return $value;
        }

        $refData = $this->hydrator->extract($value);

        if (!isset($refData[$refColumn])) {
            throw new \RuntimeException(
                sprintf(
                    'Could not extract referenced join column %s#%s',
                    $target,
                    $refColumn
                )
            );
        }

        $value = $refData[$refColumn];

        return $value;
    }

    /**
     * @todo Call me
     * @param  mixed  $valueOrObject
     * @param  string $target
     * @param  string $joinColumn
     * @return array
     */
    protected function fromMany($values, $target, $refColumn)
    {
        if (!is_array($values) && !$values instanceof Traversable) {
            $values = (array) $values;
        } elseif ($values instanceof Traversable) {
            $values = ArrayUtils::iteratorToArray($values);
        }

        foreach ($values as $key => &$value) {
            $value = $this->fromOne($value, $target, $refColumn);

            if ($value === false) {
                unset($values[$key]);
            }
        }

        return $values;
    }

    /**
     * @param  string    $target
     * @param  mixed     $identifiers
     * @return object
     */
    protected function find($target, $identifiers)
    {
        return $this->objectManager->find($target, $identifiers);
    }
}
