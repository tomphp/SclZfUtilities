<?php

namespace SclZfUtiltiesTests\Model;

use SclZfUtilities\Model\CurrencyValue;

class CurrencyValueTest extends \PHPUnit_Framework_TestCase
{
    /**
     * testSimpleGettingAndSetting
     *
     * @covers SclZfUtilities\Model\CurrencyValue::__construct
     * @covers SclZfUtilities\Model\CurrencyValue::set
     * @covers SclZfUtilities\Model\CurrencyValue::get
     * @covers SclZfUtilities\Model\CurrencyValue::multiplier
     * @covers SclZfUtilities\Model\CurrencyValue::amountFromPriceOrScalar
     *
     * @return void
     */
    public function testSimpleGettingAndSetting()
    {
        $value = new CurrencyValue(25.50);

        $this->assertEquals(
            25.50,
            $value->get(),
            'Value set by constructor is incorrect.'
        );

        $value->set(69.1);

        $this->assertEquals(
            69.1,
            $value->get(),
            'Value set by set() method is incorrect'
        );

        $value->set(new CurrencyValue(77.75));

        $this->assertEquals(
            77.75,
            $value->get(),
            'Value set by set() method with object is incorrect'
        );
    }

    /**
     * Test the add method.
     *
     * @depends testSimpleGettingAndSetting
     * @covers  SclZfUtilities\Model\CurrencyValue::add
     * @covers  SclZfUtilities\Model\CurrencyValue::toInt
     * @covers  SclZfUtilities\Model\CurrencyValue::multiplier
     * @covers  SclZfUtilities\Model\CurrencyValue::amountFromPriceOrScalar
     *
     * @return void
     */
    public function testAdd()
    {
        $value = new CurrencyValue(22.45);

        $result = $value->add(2.0);

        $this->assertEquals(
            $value,
            $result,
            'Add did not return $this.'
        );

        $this->assertEquals(
            24.45,
            $value->get(),
            'Add with scalar gave incorrect value.'
        );

        $value->add(new CurrencyValue(5.5));

        $this->assertEquals(
            29.95,
            $value->get(),
            'Add with CurrencyValue gave incorrect value.'
        );
    }

    /**
     * Test the subtract method.
     *
     * @depends testSimpleGettingAndSetting
     * @covers  SclZfUtilities\Model\CurrencyValue::subtract
     * @covers  SclZfUtilities\Model\CurrencyValue::toInt
     * @covers  SclZfUtilities\Model\CurrencyValue::multiplier
     * @covers  SclZfUtilities\Model\CurrencyValue::amountFromPriceOrScalar
     *
     * @return void
     */
    public function testSubtract()
    {
        // 69.1 - 69 in php causes floating point error

        $value = new CurrencyValue(69.1);

        $result = $value->subtract(69);

        $this->assertEquals(
            $value,
            $result,
            'Subtract did not return $this.'
        );

        $this->assertEquals(
            0.1,
            $value->get(),
            'Subtract with scalar gave incorrect value.'
        );

        $value->subtract(new CurrencyValue(5.5));

        $this->assertEquals(
            -5.4,
            $value->get(),
            'Subtract with CurrencyValue gave incorrect value.'
        );
    }
}
