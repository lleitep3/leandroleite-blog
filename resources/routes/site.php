<?php

use Service\View;

$router->get('/', function() {
            View::set('page', 'institucional_home');
            View::render();
        });

$router->get('/leandroleite', function() {
            View::set('page', 'institucional_sobremim');
            View::render();
        });

$router->get('/artigos', function() {
            View::set('page', 'institucional_artigos');
            View::render();
        });

$router->get('/projetos', function() {
            View::set('page', 'institucional_projetos');
            View::render();
        });

$router->get('/phpinfo', function() {
            phpinfo();
            exit;
        });