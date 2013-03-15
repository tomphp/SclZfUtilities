<?php

namespace SclZfUtilities\View\Helper;

use Zend\View\Helper\AbstractHelper;

class PageTitle extends AbstractHelper
{
    const TITLE_PLACEHOLDER = 'page-title';

    /**
     *
     * @param string $title
     * @param string $subtitle
     * @return PageTitle
     */
    public function __invoke($title = null, $subtitle = null)
    {
        if (null === $title) {
            return $this;
        }

        $headTitle = $title;
        $this->view->placeholder(self::TITLE_PLACEHOLDER)->title = $title;

        if (null !== $subtitle) {
            $headTitle .= ' :: ' . $subtitle;
            $this->view->placeholder(self::TITLE_PLACEHOLDER)->subtitle = $subtitle;
        }

        $this->view->headTitle($headTitle);

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $subtitle = '';

        if (isset($this->view->placeholder(self::TITLE_PLACEHOLDER)->subtitle)) {
            $subtitle = '<small>'
                . $this->view->placeholder(self::TITLE_PLACEHOLDER)->subtitle
                . '</small>';
        }

        return '<div class="page-header"><h1>'
            . $this->view->placeholder(self::TITLE_PLACEHOLDER)->title
            . ' '
            . $subtitle
            . '</h1></div>';
    }
}
