<?php

return array(
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOSqlite\Driver',
                'params' => array(
                    'dbname'    => ':memory:',
                ),
            ),
        ),
    ),

    /*
    'service_manager' => array(
        'factories' => array(
            'Request' => function ($sm) {
                return new \Zend\Http\Request();
            }
        ),
    ),
    */
);
