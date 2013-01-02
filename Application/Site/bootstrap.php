<?php

set_include_path(
    get_include_path() . PATH_SEPARATOR .
    realpath(__DIR__ . '/../../Application') . PATH_SEPARATOR .
    realpath(__DIR__ . '/../../Vendor')
);

require_once realpath(__DIR__ . '/../../Vendor/Respect/Loader.php');
spl_autoload_register(new Respect\Loader());
\ProjectTest\Service\View::setTemplatePath(realpath(__DIR__ . '/../../resources/template/'));
$router = new \Respect\Rest\Router();

$path = realpath(__DIR__ . '/../../resources/routes');
$routes = new DirectoryIterator($path);

foreach ($routes as $r) {
    if ($r->isFile())
        include $r->getPathname();
}

return $router->dispatch()->response();