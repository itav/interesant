<?php

use Itav\Component\Mysql\MysqliDriver;
use Itav\Component\Serializer\Serializer;
use Symfony\Component\Templating\PhpEngine;
use Symfony\Component\Templating\TemplateNameParser;
use Symfony\Component\Templating\Loader\FilesystemLoader;
use Symfony\Component\Templating\Helper\SlotsHelper;

//$app = new Silex\Application();
require_once 'config.php';

$app['db'] = function($app) {
    return new MysqliDriver($app['db_host'], $app['db_user'], $app['db_pass'], $app['db_name']);
};

$app['serializer'] = function() {
    return new Serializer();
};

$app['templating'] = function($app) {
    $loader = new FilesystemLoader($app['view_dirs']);
    $templating =  new PhpEngine(new TemplateNameParser(), $loader);
    $templating->set(new SlotsHelper());
    return $templating;
};
