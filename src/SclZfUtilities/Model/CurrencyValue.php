<?php
/**
 * Contains the CurrencyValue class definition.
 *
 * @author Tom Oram
 */
namespace SclZfUtilities\Model;

/**
 *
 * @author Tom Oram
 * @todo Implement some cool pricey method
 */
class CurrencyValue
{
    const PRECISION = 2;

    /**
     * @var integer
     */
    private $amount;

    /**
     * Constructor
     */
    public function __construct($amount = 0)
    {
        $this->set($amount);
    }

    public function __toString()
    {
        $format = '%.0' . self::PRECISION . 'f';
        return sprintf($format, $this->get());
    }

    protected function multiplier()
    {
        return pow(10, self::PRECISION);
    }

    protected function getAmountFromPriceOrScalar($priceOrScalar)
    {
        if ($priceOrScalar instanceof CurrencyValue) {
            return $priceOrScalar->get();
        }

        return $priceOrScalar;
    }

    public function set($amount)
    {
        $this->amount = intval($amount * $this->multiplier());
        return $this;
    }

    public function get()
    {
        return $this->amount / $this->multiplier();
    }

    public function add($amountToAdd)
    {
        $amount = $this->getAmountFromPriceOrScalar($amountToAdd);
        $this->amount += intval($amount * $this->multiplier());
        return $this;
    }

    public function subtract($amountToSubtract)
    {
        $amount = $this->getAmountFromPriceOrScalar($amountToSubtract);
        $this->amount -= intval($amount * $this->multiplier());
        return $this;
    }

    public function applyTax($percentage)
    {
        throw new \Exception('Implement me');
    }
}
