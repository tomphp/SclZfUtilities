<?php
/**
 * This file contains the definition of the Url class
 *
 * @author Tom Oram
 */

namespace SclZfUtilities\View\Helper;

/**
 * This class is a utility class for view helpers that want to return a URL to
 * be displayed.
 *
 * @author Tom Oram
 */
class Url
{
    private $url;

    private $view;

    public function __construct($view, $url)
    {
        $this->view = $view;
        $this->url = $url;
    }

    public function __toString()
    {
        return $this->url;
    }

    protected function buildLink($text, $tooltip = null)
    {
        if ($tooltip === null) {
            return sprintf('<a href="%s">%s</a>', $this->url, $this->view->escapeHtml($text));
        } else {
            return sprintf(
                '<a href="%s" rel="tooltip" title="%s">%s</a>',
                $this->url,
                $this->view->escapeHtml($tooltip),
                $text
            );
        }
    }

    public function link($text, $tooltip = null)
    {
        return $this->buildLink(
            $this->view->escapeHtml($text),
            $tooltip
        );
    }

    public function iconLink($iconName, $tooltip = null)
    {
        return $this->buildLink('<i class="' . $this->view->escapeHtml($iconName) . '"></i>', $tooltip);
    }
}
