<?php

namespace SclZfUtilities\Model;

class ValueMatch
{
    /**
     *
     * @var DisplayValue
     */
    protected $valueOne;

    /**
     *
     * @var DisplayValue
     */
    protected $valueTwo;

    /**
     *
     * @param mixed $valueOne
     * @param mixed $valueTwo
     */
    public function __construct($valueOne, $valueTwo)
    {
        $this->valueOne = $this->processValue($valueOne);
        $this->valueTwo = $this->processValue($valueTwo);
    }

    /**
     *
     * @param mixed $value
     * @param mixed $display
     * @return DisplayValue
     */
    private function processValue($value, $display = null)
    {
        if ($value instanceof DisplayValue) {
            return $value;
        }

        return new DisplayValue($value, $display);
    }

    /**
     *
     * @param mixed $value
     * @param mixed $display
     * @return ValueMatch
     */
    public function setValueOne($value, $display = null)
    {
        $this->valueOne = $this->processValue($value, $display);
        return $this;
    }

    /**
     *
     * @param mixed $value
     * @param mixed $display
     * @return ValueMatch
     */
    public function setValueTwo($value, $display = null)
    {
        $this->valueTwo = $this->processValue($value, $display);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValueOne()
    {
        return $this->valueOne->value;
    }

    /**
     * @return mixed
     */
    public function displayValueOne()
    {
        return $this->valueOne->display;
    }

    /**
     * @return mixed
     */
    public function getValueTwo()
    {
        return $this->valueTwo->value;
    }

    /**
     * @return mixed
     */
    public function displayValueTwo()
    {
        return $this->valueTwo->display;
    }

    /**
     * @return boolean
     */
    public function match()
    {
        return $this->valueOne->value == $this->valueTwo->value;
    }
}
