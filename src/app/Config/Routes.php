<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ============================================================================
// PUBLIC ROUTES
// ============================================================================
$routes->get('/', 'Home::index');
$routes->get('/health', 'Api\\HealthController::index');
$routes->get('/docs', 'SwaggerController::index', ['as' => 'swagger_docs']);

// ============================================================================
// AUTHENTICATION ROUTES (Frontend)
// ============================================================================
$routes->group('', static function ($routes) {
    // Login
    $routes->get('login', 'AuthController::login');
    $routes->post('login', 'AuthController::processLogin', ['filter' => 'rate-limit']);

    // Register
    $routes->get('register', 'AuthController::register');
    $routes->post('register', 'AuthController::processRegister');

    // Logout
    $routes->get('logout', 'AuthController::logout');

    // Profile
    $routes->get('profile', 'AuthController::profile');
});

// ============================================================================
// DASHBOARD ROUTES
// ============================================================================
$routes->group('', static function ($routes) {
    $routes->get('dashboard', 'DashboardController::index');
    $routes->get('dashboard/admin', 'DashboardController::admin');
    $routes->get('dashboard/teacher', 'DashboardController::teacher');
    $routes->get('dashboard/student', 'DashboardController::student');
});

// ============================================================================
// MANAGEMENT ROUTES
// ============================================================================
$routes->group('', static function ($routes) {
    // Users Management
    $routes->get('users', 'UsersController::index');
    $routes->get('users/create', 'UsersController::create');
    $routes->post('users', 'UsersController::store');
    $routes->get('users/(:num)', 'UsersController::show/$1');
    $routes->get('users/(:num)/edit', 'UsersController::edit/$1');
    $routes->put('users/(:num)', 'UsersController::update/$1');
    $routes->delete('users/(:num)', 'UsersController::delete/$1');

    // Schedules Management
    $routes->get('schedules', 'SchedulesController::index');
    $routes->get('schedules/calendar', 'SchedulesController::calendar');
    $routes->get('schedules/create', 'SchedulesController::create');
    $routes->post('schedules', 'SchedulesController::store');
    $routes->get('schedules/(:num)', 'SchedulesController::show/$1');
    $routes->get('schedules/(:num)/edit', 'SchedulesController::edit/$1');
    $routes->put('schedules/(:num)', 'SchedulesController::update/$1');
    $routes->delete('schedules/(:num)', 'SchedulesController::delete/$1');

    // Settings
    $routes->get('settings', 'SettingsController::index');
    $routes->post('settings/(:segment)', 'SettingsController::save/$1');
});

// ============================================================================
// API ROUTES
// ============================================================================
$routes->group('api', ['namespace' => 'App\\Controllers\\Api'], static function ($routes) {

    // ------------------------------------------------------------------------
    // AUTHENTICATION (FU-AUTH-01)
    // ------------------------------------------------------------------------
    $routes->post('auth/login', 'AuthController::login');
    $routes->post('auth/logout', 'AuthController::logout');
    $routes->post('auth/refresh', 'AuthController::refresh');
    $routes->get('auth/profile', 'AuthController::profile', ['filter' => 'jwt-auth']);
    $routes->post('auth/change-password', 'AuthController::changePassword', ['filter' => 'jwt-auth']);

    // ------------------------------------------------------------------------
    // SCHEDULES (FU-02, FU-05)
    // ------------------------------------------------------------------------
    $routes->get('schedules', 'SchedulesController::index', ['filter' => 'jwt-auth']);
    $routes->get('schedules/calendar', 'SchedulesController::calendar', ['filter' => 'jwt-auth']);
    $routes->get('schedules/(:num)', 'SchedulesController::show/$1', ['filter' => 'jwt-auth']);
    $routes->post('schedules', 'SchedulesController::store', ['filter' => 'jwt-auth']);
    $routes->put('schedules/(:num)', 'SchedulesController::update/$1', ['filter' => 'jwt-auth']);
    $routes->delete('schedules/(:num)', 'SchedulesController::delete/$1', ['filter' => 'jwt-auth']);

    // Legacy schedule routes
    $routes->get('schedules/class/(:num)', 'ScheduleController::byClass/$1');
    $routes->get('schedules/teacher/(:num)', 'ScheduleController::byTeacher/$1');
    $routes->get('schedules/search', 'ScheduleController::search');
    $routes->post('schedules/generate', 'ScheduleController::generate');
    $routes->post('schedules/apply', 'ScheduleController::apply');

    // ------------------------------------------------------------------------
    // USERS MANAGEMENT
    // ------------------------------------------------------------------------
    $routes->get('users', 'UsersController::index', ['filter' => 'jwt-auth']);
    $routes->get('users/(:num)', 'UsersController::show/$1', ['filter' => 'jwt-auth']);
    $routes->post('users', 'UsersController::store', ['filter' => 'jwt-auth']);
    $routes->put('users/(:num)', 'UsersController::update/$1', ['filter' => 'jwt-auth']);
    $routes->delete('users/(:num)', 'UsersController::delete/$1', ['filter' => 'jwt-auth']);

    // ------------------------------------------------------------------------
    // SUBJECTS & ROOMS
    // ------------------------------------------------------------------------
    $routes->get('subjects', 'SubjectsController::index', ['filter' => 'jwt-auth']);
    $routes->get('rooms', 'RoomsController::index', ['filter' => 'jwt-auth']);

    // ------------------------------------------------------------------------
    // SETTINGS
    // ------------------------------------------------------------------------
    $routes->get('settings', 'SettingsController::index', ['filter' => 'jwt-auth']);
    $routes->post('settings/(:segment)', 'SettingsController::save/$1', ['filter' => 'jwt-auth']);

    // ------------------------------------------------------------------------
    // TEACHING ASSIGNMENTS (FU-03)
    // ------------------------------------------------------------------------
    $routes->get('assignments', 'TeachingAssignmentController::list');
    $routes->get('assignments/class/(:num)', 'TeachingAssignmentController::listByClass/$1');
    $routes->get('assignments/teacher/(:num)', 'TeachingAssignmentController::listByTeacher/$1');
    $routes->post('assignments', 'TeachingAssignmentController::create');
    $routes->put('assignments/(:num)', 'TeachingAssignmentController::update/$1');
    $routes->delete('assignments/(:num)', 'TeachingAssignmentController::delete/$1');

    // ------------------------------------------------------------------------
    // ATTENDANCE (FU-06)
    // ------------------------------------------------------------------------
    $routes->get('attendance/schedule/(:num)', 'AttendanceController::listBySchedule/$1');
    $routes->post('attendance/schedule/(:num)', 'AttendanceController::markForSchedule/$1');

    // ------------------------------------------------------------------------
    // NOTIFICATIONS (FU-07)
    // ------------------------------------------------------------------------
    $routes->get('notifications/user/(:num)', 'NotificationController::listByUser/$1');
    $routes->post('notifications', 'NotificationController::create');
    $routes->post('notifications/(:num)/read', 'NotificationController::markRead/$1');

    // ------------------------------------------------------------------------
    // USER MANAGEMENT (FU-01)
    // ------------------------------------------------------------------------
    $routes->get('users', 'UserController::index');
    $routes->get('users/(:num)', 'UserController::show/$1');
    $routes->post('users', 'UserController::create');
    $routes->put('users/(:num)', 'UserController::update/$1');
    $routes->delete('users/(:num)', 'UserController::delete/$1');
});
