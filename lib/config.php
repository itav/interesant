<?php

//$app = new Silex\Application();

$app['db_host'] = 'localhost';
$app['db_user'] = 'root';
$app['db_pass'] = 'adminbsd';
$app['db_name'] = 'interesant';

$app['view_dirs'] = [
    __DIR__ . '/../src/views/%name%',
    __DIR__ . '/../vendor/itav/form/src/views/php/%name%',
];

