<?php

/* settings/database.php */

return array(
    'mysql' => array(
        'dbdriver' => 'mysql',
        'username' => 'root',
        'password' => '',
        'dbname' => 'eleave',
        'prefix' => 'eleave',
    ),
    'tables' => array(
        'user' => 'user',
        'category' => 'category',
        'language' => 'language',
        'leave' => 'leave',
        'leave_items' => 'leave_items',
    ),
);
