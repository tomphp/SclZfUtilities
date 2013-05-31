<?php

namespace SclZfUtilitiesTests\Doctrine;

use SclZfUtilities\Doctrine\FlushLock;

class FlushLockTest extends \PHPUnit_Framework_TestCase
{
    protected $flushLock;

    protected $entityManager;

    protected function setUp()
    {
        $this->entityManager = $this->getMock('Doctrine\Common\Persistence\ObjectManager');

        $this->flushLock = new FlushLock($this->entityManager);
    }

    public function testLockUnlock()
    {
        $this->entityManager
             ->expects($this->once())
             ->method('flush');

        $this->flushLock->lock();
        $this->flushLock->lock();

        $this->assertFalse($this->flushLock->unlock(), 'Returned true on first unlock.');

        $this->assertTrue($this->flushLock->unlock(), 'Returned false on final unlock.');
    }

    public function testUnlockTooFar()
    {
        $this->entityManager
             ->expects($this->never())
             ->method('flush');

        $this->assertFalse($this->flushLock->unlock(), 'Returned true when unlocking beyond 0.');
    }
}
