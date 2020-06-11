<?php
/**
 * =============================================================================================
 *  Project: sssm-core
 *  File: Routes.php
 *  Date: 2020/05/21 19:19
 *  Author: Shoji Ogura <kohenji@sarahsytems.com>
 *  Copyright (c) 2020. Shoji Ogura
 *  This software is released under the MIT License, see LICENSE.txt.
 * =============================================================================================
 */

$routes->group('', ['namespace' => 'Sssm\Base\Controllers'], function($routes) {
    $routes->add('Api', 'Api::index');
    $routes->add('Cli', 'Cli::index');
    $routes->add('Api/(:any)', 'Api::index/$1');
    $routes->add('Cli/(:any)', 'Cli::index/$1');
});

