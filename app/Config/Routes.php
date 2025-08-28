<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/generate-quotes', 'GenerateQuotes::index');
// User
$routes->get('/management-user', 'User::index');
$routes->post('/list-user', 'User::list_data');
$routes->post('/save-user', 'User::save');
$routes->post('/edit-user', 'User::edit');
$routes->post('/update-user', 'User::update');
$routes->post('/delete-user', 'User::delete');
