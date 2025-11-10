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
$routes->get('/proveedores', 'Proveedores::index');


//zonas
$routes->get('/zonas', 'Zonas::index');
$routes->get('/zonas/nuevo', 'Zonas::nuevo');
$routes->post('/zonas/guardar', 'Zonas::guardar');
$routes->get('/zonas/borrar/(:num)', 'Zonas::borrar/$1');
$routes->get('/zonas/editar/(:num)', 'Zonas::editar/$1');
$routes->post('/zonas/actualizar/(:num)', 'Zonas::actualizar/$1');

$routes->get('/usuarios', 'Usuarios::index');
$routes->get('/usuarios/nuevo', 'Usuarios::nuevo');
$routes->post('/usuarios/guardar', 'Usuarios::guardar');
$routes->get('/usuarios/borrar/(:num)', 'Usuarios::borrar/$1');
$routes->get('/usuarios/editar/(:num)', 'Usuarios::editar/$1');
$routes->post('/usuarios/actualizar/(:num)', 'Usuarios::actualizar/$1');

//socios
$routes->get('/socios', 'Socios::index');
$routes->get('/socios/nuevo', 'Socios::nuevo');
$routes->post('/socios/guardar', 'Socios::guardar');
$routes->get('/socios/borrar/(:num)', 'Socios::borrar/$1');
$routes->get('/socios/editar/(:num)', 'Socios::editar/$1');
$routes->post('/socios/actualizar/(:num)', 'Socios::actualizar/$1');

//cajas
$routes->get('/cajas', 'Caja::index');
$routes->get('/cajas/nuevo', 'Caja::nuevo');
$routes->post('/cajas/guardar', 'Caja::guardar');
$routes->get('/cajas/borrar/(:num)', 'Caja::borrar/$1');
$routes->get('/cajas/editar/(:num)', 'Caja::editar/$1');
$routes->post('/cajas/actualizar/(:num)', 'Caja::actualizar/$1');

//recaudadores
$routes->get('/recaudadores', 'Recaudadores::index');
$routes->get('/recaudadores/nuevo', 'Recaudadores::nuevo');
$routes->post('/recaudadores/guardar', 'Recaudadores::guardar');
$routes->get('/recaudadores/borrar/(:num)', 'Recaudadores::borrar/$1');
$routes->get('/recaudadores/editar/(:num)', 'Recaudadores::editar/$1');
$routes->post('/recaudadores/actualizar/(:num)', 'Recaudadores::actualizar/$1');

$routes->get('/liquidaciones', 'Liquidaciones::index');
$routes->get('/liquidaciones/nuevo', 'Liquidaciones::nuevo');
$routes->post('/liquidaciones/guardar', 'Liquidaciones::guardar');
$routes->get('/liquidaciones/borrar/(:num)', 'Liquidaciones::borrar/$1');
$routes->get('/liquidaciones/editar/(:num)', 'Liquidaciones::editar/$1');
$routes->post('/liquidaciones/actualizar/(:num)', 'Liquidaciones::actualizar/$1');


//login
$routes->get('/login', 'Login::index');
$routes->post('/login/validar', 'Login::validar');
$routes->get('/login/logout', 'Login::logout');