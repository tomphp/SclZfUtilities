<?php

namespace SclZfUtilities\Doctrine;

use Doctrine\Common\Persistence\ObjectManager;

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
     * @var ObjectManager
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
     * @param ObjectManager $entityManager
     */
    public function __construct(ObjectManager $entityManager)
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

        if ($this->count < 0) {
            $this->count = 0;
            return false;
        }

        if (0 === $this->count) {
            $this->entityManager->flush();

            return true;
        }

        return false;
    }
}
