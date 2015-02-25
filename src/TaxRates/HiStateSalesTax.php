<?php namespace Ammonkc\Basket\TaxRates;

use PhilipBrown\Basket\TaxRate;

class HiStateSalesTax implements TaxRate
{
    /**
     * @var float
     */
    private $rate;

    /**
     * Create a new Tax Rate
     *
     * @return void
     */
    public function __construct()
    {
        $this->rate = 0.04;
    }

    /**
     * Return the Tax Rate as a float
     *
     * @return float
     */
    public function float()
    {
        return $this->rate;
    }

    /**
     * Return the Tax Rate as a percentage
     *
     * @return int
     */
    public function percentage()
    {
        return intval($this->rate * 100);
    }
}
