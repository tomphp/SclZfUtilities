<?php

namespace SclZfUtilities\View\Helper;

use Zend\View\Helper\AbstractHelper;
use DateTime;

class FormatDate extends AbstractHelper
{
    const FORMAT = 'd/m/Y';

    public function __invoke(DateTime $date, $class = 'date')
    {
        return sprintf('<span class="%s">%s</span>', $class, $date->format(self::FORMAT));
    }
}
