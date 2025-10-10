<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/panel', 'Panel::index');
$routes->get('/panel/index', 'Panel::index');
$routes->get('/panel/nuevo', 'Panel::nuevo');
$routes->get('/paises', 'Paises::index');
$routes->get('/paises/listar', 'Paises::listar');
$routes->get('/clientes', 'Clientes::index');
$routes->get('/proveedores', 'Proveedores::index');
$routes->get('/clientes/nuevo', 'Clientes::nuevo');
$routes->post('/clientes/guardar', 'Clientes::guardar');
$routes->get('/clientes/borrar/(:num)', 'Clientes::borrar/$1');
$routes->get('/clientes/editar/(:num)', 'Clientes::editar/$1');
$routes->post('/clientes/actualizar/(:num)', 'Clientes::actualizar/$1');




