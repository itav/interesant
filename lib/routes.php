<?php

//$app = new Silex\Application();

$app->get('/list', 'App\\InteresantController::listAction');
$app->get('/form', 'App\\InteresantController::formAction');
$app->get('/add', 'App\\InteresantController::addAction');
$app->post('/add', 'App\\InteresantController::saveAction')->bind('interesant_add');
$app->get('/info/{id}', 'App\\InteresantController::infoAction')->assert('id', '\w+');
$app->get('/edit/{id}', 'App\\InteresantController::addAction')->assert('id', '\w+');
$app->put('/edit/{id}', 'App\\InteresantController::saveAction')->assert('id', '\w+');
$app->delete('/del/{id}', 'App\\InteresantController::deleteAction')->assert('id', '\w+');
