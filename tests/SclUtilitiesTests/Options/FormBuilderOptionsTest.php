<?php

namespace SclZfUtilitiesTests\Options;

use SclZfUtilities\Options\FormBuilderOptions;

/**
 * Unit tests for {@see FormBuilderOptions}.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class FormBuilderOptionsTest extends \PHPUnit_Framework_TestCase
{
    protected $options;

    protected function setUp()
    {
        $this->options = new FormBuilderOptions();
    }

    /**
     * Tests the get and set for formEntityMap.
     *
     * @covers SclZfUtilities\Options\FormBuilderOptions::setFormEntityMap
     * @covers SclZfUtilities\Options\FormBuilderOptions::getFormEntityMap
     *
     * @return void
     */
    public function testFormEntityMap()
    {
        $map = array('entity1' => 'form1');

        $result = $this->options->setFormEntityMap($map);

        $this->assertSame($this->options, $result, 'setFormEntityMap didn\'t return $this');

        $result = $this->options->getFormEntityMap();

        $this->assertEquals($map, $result, 'getFormEntityMap didn\'t return correct value');
    }
}
