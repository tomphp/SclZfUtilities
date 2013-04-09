<?php

namespace SclZfUtilities\Doctrine;

use Doctrine\ORM\EntityManager;

/**
 * This calls flush on the doctrine EntityManager once on sub-persistence
 * functions have completed.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class FlushLock
{
    /**
     * The Doctrine EntityManager.
     *
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * The number of levels the lock is currently at.
     *
     * @var int
     */
    protected $count = 0;

    /**
     * Inject the EntityManager.
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Call this at the start of a persistence function.
     *
     * @return void
     */
    public function lock()
    {
        $this->count++;
    }

    /**
     * Call this at the end of a persistence function.
     *
     * @return boolean True if flush was called.
     */
    public function unlock()
    {
        $this->count--;

        if (0 === $this->count) {
            $this->entityManager->flush();

            return true;
        }

        return false;
    }
}
