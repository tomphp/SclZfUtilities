<?php
/**
 * Contains the CurrencyValue class definition.
 *
 * @author Tom Oram
 */
namespace SclZfUtilities\Model;

use SclZfUtilities\Exception\InvalidArgumentException;

/**
 * Class for representing and performing calculations on currency.
 *
 * @author Tom Oram
 * @todo Implement some cool pricey method
 */
class CurrencyValue
{
    /**
     * The number of decimal places used.
     */
    const PRECISION = 2;

    /**
     * @var int
     */
    private $amount;

    /**
     * Constructor
     *
     * @param number $amount
     */
    public function __construct($amount = 0)
    {
        $this->set($amount);
    }

    /**
     * Convert the amount to a formatted string.
     *
     * @return string
     */
    public function __toString()
    {
        $format = '%.0' . self::PRECISION . 'f';

        return sprintf($format, $this->get());
    }

    /**
     * The value used to multiply the currency by to represent it as an integer.
     *
     * @return int
     */
    protected static function multiplier()
    {
        return pow(10, self::PRECISION);
    }

    /**
     * Safely convert a floating point number to an integer.
     *
     * @param  mixed $value
     * @return int
     */
    protected function toInt($value)
    {
        return (int) round($value);
    }

    /**
     * If the parameter passed in as instance of CurrencyValue then extract the amount.
     *
     * @param  number|CurrencyValue $objectOrScalar
     * @return number
     * @throws InvalidArgumentException If $objectOrScalar is an instance of something other than CurrencyValue
     */
    protected function amountFromPriceOrScalar($objectOrScalar)
    {
        if (is_object($objectOrScalar) && !$objectOrScalar instanceof CurrencyValue) {
            throw new InvalidArgumentException(
                'Expected instance of ' . __CLASS__ . ' but got '
                . get_class($objectOrScalar) . ' in ' . __METHOD__
            );
        }

        if ($objectOrScalar instanceof CurrencyValue) {
            return $objectOrScalar->get();
        }

        return $objectOrScalar;
    }

    public function set($objectOrScalar)
    {
        $amount = $this->amountFromPriceOrScalar($objectOrScalar);

        $this->amount = $this->toInt($amount * self::multiplier());

        return $this;
    }

    public function get()
    {
        return $this->amount / self::multiplier();
    }

    public function add($amountToAdd)
    {
        $amount = $this->amountFromPriceOrScalar($amountToAdd);

        $this->amount += $this->toInt($amount * self::multiplier());

        return $this;
    }

    public function subtract($amountToSubtract)
    {
        $amount = $this->amountFromPriceOrScalar($amountToSubtract);

        $this->amount -= $this->toInt($amount * self::multiplier());

        return $this;
    }

    public function applyTax($percentage)
    {
        throw new \Exception('Implement me');
    }
}
