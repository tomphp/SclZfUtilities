<?php

namespace SclZfUtilities\Mapper;

/**
 * Basic interface for basic mapper class for doctrine storage.
 *
 * @author Tom Oram <tom@scl.co.uk>
 * @author Fee
 */
interface GenericMapperInterface
{
    /**
     * Creates a new instance of the entity.
     *
     * @return object
     */
    public function create();

    /**
     * Persists to the Order to storage.
     *
     * @param  object $order
     * @return boolean
     */
    public function save($order);

    /**
     * Loads a given order from the database.
     *
     * @param  mixed $id
     * @return object|null
     */
    public function findById($id);

    /**
     * Returns all orders from the database.
     *
     * @return object[]|null
     */
    public function fetchAll();

    /**
     * Does a search by criteria.
     *
     * @param  array $criteria
     * @return object[]|null
     */
    public function fetchBy(array $criteria);

    /**
     * Deletes the order from the storage.
     *
     * @param  object $entity
     * @return boolean
     */
    public function delete($entity);
}
