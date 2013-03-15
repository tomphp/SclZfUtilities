<?php

namespace SclZfUtilities\View\Helper;

use Zend\View\Helper\AbstractHelper;

class FormatPrice extends AbstractHelper
{
    const FORMAT = '<span class="%s"><span class="currency-symbol">%s</span>%.02f</span>';
    const SYMBOL = '&pound;';

    public function __invoke($price, $showSymbol = true, $class = 'currency-amount')
    {
        if ($showSymbol) {
            return sprintf(
                self::FORMAT,
                $class,
                self::SYMBOL,
                $price
            );
        }

        return sprintf('<span class="%s">%.02f</span>', $class, $price);
    }
}
