<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/health', 'HealthController::index');
$routes->group('api', static function ($routes) {
    $routes->get('schedules/class/(:num)', 'ScheduleController::byClass/$1');
    $routes->get('schedules/teacher/(:num)', 'ScheduleController::byTeacher/$1');
});
