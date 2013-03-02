<?php

error_reporting(E_ALL);
set_include_path(
        get_include_path() . PATH_SEPARATOR .
        realpath(__DIR__ . '/../../Vendor') . PATH_SEPARATOR .
        realpath(__DIR__ . '/../../Application/Site')
);

define('PATH_SETTINGS', realpath(__DIR__ . '/../../settings/'));

// auto Loader initialize
require_once realpath(__DIR__ . '/../../Vendor/Respect/Loader.php');
spl_autoload_register(new Respect\Loader());
\Service\View::setTemplatePath(realpath(__DIR__ . '/../../resources/template/'));
$router = new \Respect\Rest\Router();

// configuration Locator initialize
$configurations = json_decode(file_get_contents(PATH_SETTINGS . '/configurations.json'), true);
new Leviathan\Service\Locator($configurations);

// Routes initialize
$path = realpath(__DIR__ . '/../../resources/routes');
$routes = new DirectoryIterator($path);

foreach ($routes as $r) {
    if ($r->isFile())
        include $r->getPathname();
}

return $router->dispatch()->response();
