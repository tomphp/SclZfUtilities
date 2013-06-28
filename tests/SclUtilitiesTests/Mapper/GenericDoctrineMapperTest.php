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
     * Test that create() returns an instance of ENTITY_NAME.
     *
     * @covers SclZfUtilities\Mapper\GenericDoctrineMapper::create
     * @covers SclZfUtilities\Mapper\GenericDoctrineMapper::setEntityName
     */
    public function testCreate()
    {
        $this->assertInstanceOf(
            self::ENTITY_NAME,
            $this->mapper->create()
        );
    }

    /**
     * Given GenericDoctrineMapper is set to use entities of type self::ENTITY_NAME
     * When Save is called with object of type \stdClass
     * Then A SclZfUtilities\Exception\InvalidArgumentException should be thrown.
     *
     * @covers            SclZfUtilities\Mapper\GenericDoctrineMapper::save
     * @covers            SclZfUtilities\Mapper\GenericDoctrineMapper::setEntityName
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
     * @covers SclZfUtilities\Mapper\GenericDoctrineMapper::setEntityName
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

    /**
     * Test findById passes the call onto the entity manager.
     *
     * @covers SclZfUtilities\Mapper\GenericDoctrineMapper::findById
     * @covers SclZfUtilities\Mapper\GenericDoctrineMapper::setEntityName
     */
    public function testFindById()
    {
        $id = 5;
        $entity = 'ENTITY';

        $this->entityManager
             ->expects($this->once())
             ->method('find')
             ->with($this->equalTo(self::ENTITY_NAME), $this->equalTo($id))
             ->will($this->returnValue($entity));

        $result = $this->mapper->findById($id);

        $this->assertEquals($entity, $result);
    }

    /**
     * Test findAll passes the call onto the entity manager.
     *
     * @covers SclZfUtilities\Mapper\GenericDoctrineMapper::findAll
     * @covers SclZfUtilities\Mapper\GenericDoctrineMapper::setEntityName
     */
    public function testFindAll()
    {
        $results = array('entities');

        $repository = $this->getMock('Doctrine\Common\Persistence\ObjectRepository');

        $this->entityManager
             ->expects($this->once())
             ->method('getRepository')
             ->with($this->equalTo(self::ENTITY_NAME))
             ->will($this->returnValue($repository));

        $repository->expects($this->once())
                   ->method('findAll')
                   ->will($this->returnValue($results));

        $this->assertEquals($results, $this->mapper->findAll());
    }

    /**
     * Test findBy passes the call onto the entity manager.
     *
     * @covers SclZfUtilities\Mapper\GenericDoctrineMapper::findBy
     * @covers SclZfUtilities\Mapper\GenericDoctrineMapper::setEntityName
     */
    public function testFindBy()
    {
        $criteria = array('search', 'params');
        $results = array('entities');

        $repository = $this->getMock('Doctrine\Common\Persistence\ObjectRepository');

        $this->entityManager
             ->expects($this->once())
             ->method('getRepository')
             ->with($this->equalTo(self::ENTITY_NAME))
             ->will($this->returnValue($repository));

        $repository->expects($this->once())
                   ->method('findBy')
                   ->with($this->equalTo($criteria))
                   ->will($this->returnValue($results));

        $this->assertEquals($results, $this->mapper->findBy($criteria));
    }

    /**
     * Test delete that delete called with an object which isn't an instance of
     * ENTITY_NAME throws an exception.
     *
     * @covers            SclZfUtilities\Mapper\GenericDoctrineMapper::delete
     * @expectedException SclZfUtilities\Exception\InvalidArgumentException
     */
    public function testDeleteWithBadEntityClass()
    {
        $entity = new \stdClass();

        $this->entityManager
             ->expects($this->never())
             ->method('remove');

        $this->mapper->delete($entity);
    }
    /**
     * Test delete passes request on to entity manager.
     *
     * @covers SclZfUtilities\Mapper\GenericDoctrineMapper::delete
     */
    public function testDelete()
    {
        $entity = new \stdClass();

        $this->mapper->setEntityName('stdClass');

        $this->entityManager
             ->expects($this->once())
             ->method('remove')
             ->with($this->equalTo($entity));

        $this->mapper->delete($entity);
    }
}
