<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\GymController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\AiController;

/**
 * Auth
 */
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

/**
 * Public Data Endpoints
 */
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);

Route::get('/trainers', [TrainerController::class, 'index']);
Route::get('/trainers/{id}', [TrainerController::class, 'show']);

Route::get('/gyms', [GymController::class, 'index']);
Route::get('/gyms/{id}', [GymController::class, 'show']);

Route::get('/plans', [PlanController::class, 'index']);

// Content & Library
Route::get('/muscles', [ContentController::class, 'muscles']);
Route::get('/video-categories', [ContentController::class, 'videoCategories']);
Route::get('/videos', [ContentController::class, 'videos']);
Route::get('/videos/{id}', [ContentController::class, 'showVideo']);

Route::middleware(['auth:sanctum'])->group(function () {
    /**
     * Auth & User Profile
     */
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [UserController::class, 'getProfile']);
    
    // Dedicated role-profile routes
    Route::put('/profile', [UserController::class, 'updateProfile']);
    Route::apiResource('user/phones', UserController::class)->only(['index', 'store', 'destroy']);
    
    // Profile Updates
    Route::apiResource('members', MemberController::class)->only(['update']);
    Route::apiResource('trainers', TrainerController::class)->only(['update']);
    Route::apiResource('gyms', GymController::class)->only(['update']);
    
    // Feedback System
    Route::get('/gyms/{id}/feedback', [FeedbackController::class, 'gymFeedback']);
    Route::post('/gyms/{id}/feedback', [FeedbackController::class, 'storeGymFeedback']);
    Route::get('/trainers/{id}/feedback', [FeedbackController::class, 'trainerFeedback']);
    Route::post('/trainers/{id}/feedback', [FeedbackController::class, 'storeTrainerFeedback']);
    
    // Bookings
    Route::apiResource('bookings', BookingController::class);
    
    // Tracking & Logs
    Route::apiResource('logs', LogController::class);
    
    // E-Commerce Store
    Route::apiResource('cart', CartController::class);
    Route::apiResource('orders', OrderController::class);
    Route::apiResource('wishlist', WishlistController::class)->only(['index', 'store', 'destroy']);
    
    // Specialized Plans (Nutrition/Workout)
    Route::get('/workout-plans', [PlanController::class, 'workoutPlans']);
    Route::get('/nutrition-plans', [PlanController::class, 'nutritionPlans']);
    Route::post('/plans/subscribe', [PlanController::class, 'subscribe']);
    
    // AI Services
    Route::get('/ai-services', [AiController::class, 'index']);
    Route::post('/ai/generate-workout', [AiController::class, 'generateWorkout']);
    Route::post('/ai/generate-nutrition', [AiController::class, 'generateNutrition']);
});
