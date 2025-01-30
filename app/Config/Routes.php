<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Auth Routes
$routes->get('/', 'Auth::login');
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attemptLogin');
$routes->get('logout', 'Auth::logout');

// Dashboard
$routes->get('dashboard', 'Dashboard::index', ['filter' => 'auth']);

// User Management (Admin only)
$routes->group('users', ['filter' => 'admin'], function ($routes) {
    $routes->get('', 'Users::index');
    $routes->get('create', 'Users::create');
    $routes->post('create', 'Users::create');
    $routes->get('edit/(:num)', 'Users::edit/$1');
    $routes->post('edit/(:num)', 'Users::edit/$1');
    $routes->get('delete/(:num)', 'Users::delete/$1');
});

// Patient Management
$routes->group('patients', ['filter' => 'auth'], function ($routes) {
    $routes->get('', 'Patients::index');
    $routes->get('create', 'Patients::create', ['filter' => 'admin']);
    $routes->post('create', 'Patients::create', ['filter' => 'admin']);
    $routes->get('edit/(:num)', 'Patients::edit/$1', ['filter' => 'admin']);
    $routes->post('edit/(:num)', 'Patients::edit/$1', ['filter' => 'admin']);
    $routes->get('delete/(:num)', 'Patients::delete/$1', ['filter' => 'admin']);
});

// Medical Records Management
$routes->group('medical-records', ['filter' => 'auth'], function ($routes) {
    $routes->get('', 'MedicalRecords::index');
    $routes->get('create', 'MedicalRecords::create');
    $routes->get('create/(:segment)', 'MedicalRecords::create/$1');
    $routes->post('create/(:segment)', 'MedicalRecords::create/$1');
    $routes->get('edit/(:num)', 'MedicalRecords::edit/$1');
    $routes->post('edit/(:num)', 'MedicalRecords::edit/$1');
    $routes->get('view/(:num)', 'MedicalRecords::view/$1');
    $routes->get('complete/(:num)', 'MedicalRecords::complete/$1');
    $routes->get('icd10-details/(:segment)', 'MedicalRecords::getICD10Details/$1');
});
