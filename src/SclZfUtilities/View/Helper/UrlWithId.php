<?php
/**
 * Contains the UrlWithId view helper.
 *
 * @author Tom Oram
 */
namespace SclZfUtilities\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * A simple wrapper for the URL view help where an id parameter is passed.
 *
 * @author Tom Oram
 */
class UrlWithId extends AbstractHelper
{
    /**
     * Takes the $id and adds it to the list of params to be passed to the url view helper.
     *
     * @param string $url
     * @param integer $id
     * @param array $params
     * @return string
     */
    public function __invoke($url, $id, array $params = array())
    {
        $params['id'] = (int)$id;
        $urlHelper = $this->view->plugin('url');
        return new Url($this->view, $urlHelper($url, $params));
    }
}
