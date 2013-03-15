<?php

namespace SclZfUtilities\Model;

class DisplayValue
{
    /**
     * The value.
     *
     * @var mixed
     */
    public $value;

    /**
     * How the value should be displayed.
     *
     * @var mixed
     */
    public $display;

    /**
     *
     * @param mixed $value
     * @param mixed $display
     */
    public function __construct($value = null, $display = null)
    {
        $this->value = $value;
        $this->display = $display;
    }
}
