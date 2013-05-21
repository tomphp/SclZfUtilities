<?php

namespace SclZfUtilitiesTest\Mapper;

use SclZfUtilities\Mapper\GenericDoctrineMapper;

class GenericDoctrineMapperTest extends \PHPUnit_Framework_TestCase
{
    const ENTITY_NAME = 'SclZfUtilities\Model\DisplayValue';

    /**
     * The mapper being tested
     *
     * @var GenericDoctrineMapper
     */
    protected $mapper;

    /**
     * EntityManager mock
     *
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    protected $entityManager;

    /**
     * FlushLock mock
     *
     * @var \SclZfUtilities\Doctrine\FlushLock
     */
    protected $flushLock;

    /**
     * Prepare the objects required for testing.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->entityManager = $this->getMock('Doctrine\Common\Persistence\ObjectManager');

        $this->flushLock = $this->getMockBuilder('SclZfUtilities\Doctrine\FlushLock')
                                ->disableOriginalConstructor()
                                ->getMock();

        $this->mapper = new GenericDoctrineMapper(
            $this->entityManager,
            $this->flushLock,
            self::ENTITY_NAME
        );
    }

    /**
     * Given GenericDoctrineMapper is set to use entities of type self::ENTITY_NAME
     * When Save is called with object of type \stdClass
     * Then A SclZfUtilities\Exception\InvalidArgumentException should be thrown.
     *
     * @covers SclZfUtilities\Mapper\GenericDoctrineMapper::save
     * @expectedException SclZfUtilities\Exception\InvalidArgumentException
     */
    public function testSaveWithBadEntityType()
    {
        $entity = new \stdClass();

        $this->mapper->save($entity);
    }

    /**
     * Given GenericDoctrineMapper is set to use entities of type self::ENTITY_NAME
     * When Save is called with object of type self::ENTITY_NAME
     * Then It should be persisted by the Entity Manager
     *
     * @covers SclZfUtilities\Mapper\GenericDoctrineMapper::save
     */
    public function testSaveWithGoodEntity()
    {
        $entityType = self::ENTITY_NAME;

        $entity = new $entityType;

        $this->entityManager
             ->expects($this->once())
             ->method('persist')
             ->with($this->equalTo($entity));

        $this->mapper->save($entity);
    }
}
