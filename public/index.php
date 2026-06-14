<?php
// File: public/index.php

// Tampilkan error saat development (ubah ke 0 saat produksi)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Load bootstrapping class
require_once __DIR__ . '/../app/Core/App.php';

use App\Core\App;
use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\CategoryController;
use App\Controllers\DestinationController;
use App\Controllers\TourController;
use App\Controllers\UserController;

$app = new App();
$router = $app->getRouter();

// 1. PUBLIC ROUTES
$router->get('/', [HomeController::class, 'index']);
$router->get('/lang/id', [HomeController::class, 'setLangId']);
$router->get('/lang/en', [HomeController::class, 'setLangEn']);
$router->get('/storage/uploads/{file}', [HomeController::class, 'serveUpload']);

// 2. AUTH ROUTES
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'showRegister']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/logout', [AuthController::class, 'logout']);
$router->get('/forgot', [AuthController::class, 'showForgot']);
$router->post('/forgot', [AuthController::class, 'forgot']);
$router->get('/reset', [AuthController::class, 'showReset']);
$router->post('/reset', [AuthController::class, 'reset']);
$router->get('/verify', [AuthController::class, 'verify']);

// 3. DASHBOARD ROUTES (Universal & Multi-Role)
$router->get('/dashboard', [DashboardController::class, 'index']);
$router->post('/dashboard/guide/availability', [DashboardController::class, 'updateGuideAvailability']);
$router->post('/dashboard/booking/assign', [DashboardController::class, 'assignGuide']);
$router->post('/dashboard/reviews/moderate', [DashboardController::class, 'moderateReview']);
$router->post('/dashboard/reviews/create', [DashboardController::class, 'createReview']);
$router->post('/dashboard/sync', [DashboardController::class, 'syncDatabase']);

// 4. USER PROFILE ROUTE
$router->get('/profile/edit', [UserController::class, 'editProfile']);
$router->post('/profile/edit', [UserController::class, 'updateProfile']);

// 5. MASTER CATEGORY CRUD ROUTES (Admin Only)
$router->get('/categories', [CategoryController::class, 'index']);
$router->get('/categories/create', [CategoryController::class, 'create']);
$router->post('/categories/create', [CategoryController::class, 'store']);
$router->get('/categories/edit/{id}', [CategoryController::class, 'edit']);
$router->post('/categories/edit/{id}', [CategoryController::class, 'update']);
$router->post('/categories/delete/{id}', [CategoryController::class, 'delete']);

// 6. DESTINATIONS ROUTES
$router->get('/destinations', [DestinationController::class, 'index']); // Public list
$router->get('/destinations/detail/{slug}', [DestinationController::class, 'detail']); // Public detail & lightbox
$router->get('/admin/destinations', [DestinationController::class, 'adminIndex']); // CRUD list
$router->get('/admin/destinations/create', [DestinationController::class, 'create']);
$router->post('/admin/destinations/create', [DestinationController::class, 'store']);
$router->get('/admin/destinations/edit/{id}', [DestinationController::class, 'edit']);
$router->post('/admin/destinations/edit/{id}', [DestinationController::class, 'update']);
$router->post('/admin/destinations/delete/{id}', [DestinationController::class, 'delete']);

// 7. TOURS PACKAGES ROUTES
$router->get('/tours', [TourController::class, 'index']); // Public list
$router->get('/tours/detail/{id}', [TourController::class, 'detail']); // Public detail
$router->get('/tours/checkout/{id}', [TourController::class, 'checkout']); // Checkout page
$router->post('/tours/checkout/{id}', [TourController::class, 'processCheckout']); // Handle checkout
$router->get('/tours/booking-success/{invoice}', [TourController::class, 'bookingSuccess']); // Booking confirmation
$router->get('/admin/tours', [TourController::class, 'adminIndex']); // CRUD list
$router->get('/admin/tours/create', [TourController::class, 'create']);
$router->post('/admin/tours/create', [TourController::class, 'store']);
$router->get('/admin/tours/edit/{id}', [TourController::class, 'edit']);
$router->post('/admin/tours/edit/{id}', [TourController::class, 'update']);
$router->post('/admin/tours/delete/{id}', [TourController::class, 'delete']);

// 8. USERS CRUD ROUTES (Admin Only)
$router->get('/admin/users', [UserController::class, 'index']);
$router->get('/admin/users/create', [UserController::class, 'create']);
$router->post('/admin/users/create', [UserController::class, 'store']);
$router->get('/admin/users/edit/{id}', [UserController::class, 'edit']);
$router->post('/admin/users/edit/{id}', [UserController::class, 'update']);
$router->post('/admin/users/delete/{id}', [UserController::class, 'delete']);

// Dispatch request
$app->run();
