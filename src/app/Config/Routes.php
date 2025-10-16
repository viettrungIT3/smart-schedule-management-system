<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/health', 'Api\\HealthController::index');
$routes->group('api', ['namespace' => 'App\\Controllers\\Api'], static function ($routes) {
    $routes->get('schedules/class/(:num)', 'ScheduleController::byClass/$1');
    $routes->get('schedules/teacher/(:num)', 'ScheduleController::byTeacher/$1');
    $routes->get('schedules/search', 'ScheduleController::search');
    $routes->post('schedules/generate', 'ScheduleController::generate');
    $routes->post('schedules/apply', 'ScheduleController::apply');
    $routes->get('assignments/class/(:num)', 'TeachingAssignmentController::listByClass/$1');
    $routes->get('assignments/teacher/(:num)', 'TeachingAssignmentController::listByTeacher/$1');
    $routes->post('assignments', 'TeachingAssignmentController::create');
    $routes->get('attendance/schedule/(:num)', 'AttendanceController::listBySchedule/$1');
    $routes->post('attendance/schedule/(:num)', 'AttendanceController::markForSchedule/$1');
    $routes->get('notifications/user/(:num)', 'NotificationController::listByUser/$1');
    $routes->post('notifications', 'NotificationController::create');
    $routes->post('notifications/(:num)/read', 'NotificationController::markRead/$1');
    $routes->get('users', 'UserController::index');
    $routes->get('users/(:num)', 'UserController::show/$1');
    $routes->post('users', 'UserController::create');
    $routes->put('users/(:num)', 'UserController::updateUser/$1');
    $routes->delete('users/(:num)', 'UserController::deleteUser/$1');
});
