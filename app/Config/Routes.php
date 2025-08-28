<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
// Generate Quotes
$routes->get('/generate-quotes', 'GenerateQuotes::index');
$routes->post('/generate-video-quotes', 'GenerateQuotes::generate');
// User
$routes->get('/management-user', 'User::index');
$routes->post('/list-user', 'User::list_data');
$routes->post('/save-user', 'User::save');
$routes->post('/edit-user', 'User::edit');
$routes->post('/update-user', 'User::update');
$routes->post('/delete-user', 'User::delete');
