<?php

return array(
    'controller_plugins' => array(
        'invokables' => array(
            'formSubmitted'  => 'SclZfUtilities\Controller\Plugin\FormSubmitted',
            'getFormBuilder' => 'SclZfUtilities\Controller\Plugin\FormBuilder',
        ),
    ),

    'view_helpers' => array(
        'invokables' => array(
            'formatPrice' => 'SclZfUtilities\View\Helper\FormatPrice',
            'formatDate'  => 'SclZfUtilities\View\Helper\FormatDate',
            'idUrl'       => 'SclZfUtilities\View\Helper\UrlWithId',
            'pageTitle'   => 'SclZfUtilities\View\Helper\PageTitle',
        ),
    ),

    'scl_zf_utilities' => array(
        'entity_form_builder' => array(
            'map' => array(),
        ),
    ),
);
