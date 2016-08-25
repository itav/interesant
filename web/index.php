<?php
namespace {
    require_once __DIR__ . '/../vendor/autoload.php';
}

namespace Silex {

    use Silex\Application\TranslationTrait;

    class App extends Application
    {
        use TranslationTrait;
    }

    $app = new App();
    $app['debug'] = true;

    require_once __DIR__ . '/../lib/routes.php';
    require_once __DIR__ . '/../lib/services.php';

    $app->run();
}

