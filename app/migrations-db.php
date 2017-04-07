<?php
/**
 * Options das Migrations
 */
return array(
        'host'      => '0.0.0.0',
        'port'      => '3306',
        'driver'    => 'pdo_mysql',
        'charset'   => 'utf8',
        'dbname'    => 'tasks',
        'user'      => 'root',
        'password'  => 'root',
        'defaultTableOptions' => ['charset'=> 'utf8', 'collate' => 'utf8_general_ci'],
);