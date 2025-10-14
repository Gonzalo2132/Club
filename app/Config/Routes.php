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

//zonas
$routes->get('/zonas/nuevo', 'Zonas::nuevo');
$routes->post('/zonas/guardar', 'Zonas::guardar');
$routes->get('/zonas/borrar/(:num)', 'Zonas::borrar/$1');
$routes->get('/zonas/editar/(:num)', 'Zonas::editar/$1');
$routes->post('/zonas/actualizar/(:num)', 'Zonas::actualizar/$1');

$routes->get('/usuarios/nuevo', 'Usuarios::nuevo');
$routes->post('/usuarios/guardar', 'Usuarios::guardar');
$routes->get('/usuarios/borrar/(:num)', 'Usuarios::borrar/$1');
$routes->get('/usuarios/editar/(:num)', 'Usuarios::editar/$1');
$routes->post('/usuarios/actualizar/(:num)', 'Usuarios::actualizar/$1');

//socios
$routes->get('/socios/nuevo', 'Socios::nuevo');
$routes->post('/socios/guardar', 'Socios::guardar');
$routes->get('/socios/borrar/(:num)', 'Socios::borrar/$1');
$routes->get('/socios/editar/(:num)', 'Socios::editar/$1');
$routes->post('/socios/actualizar/(:num)', 'Socios::actualizar/$1');



