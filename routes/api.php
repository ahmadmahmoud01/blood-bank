<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BloodTypeController;
use App\Http\Controllers\Api\MainController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1'], function() {

    Route::get('blood-types', [MainController::class, 'bloodTypes']);
    Route::get('governorates', [MainController::class, 'governorates']);
    Route::get('cities', [MainController::class, 'cities']);
    Route::get('categories', [MainController::class, 'categories']);



    Route::post('clients/reset-password', [AuthController::class, 'resetPassword']);
    Route::post('clients/new-password', [AuthController::class, 'newPassword']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::group(['middleware' => 'auth:sanctum'], function() {

        Route::get('donation-requests', [MainController::class, 'donationRequests']);
        Route::post('donation-requests/create', [MainController::class, 'createDonationRequest']);
        Route::get('notifications', [MainController::class, 'notifications']);
        Route::get('settings', [MainController::class, 'settings']);
        Route::post('contacts', [MainController::class, 'contacts']);
        Route::get('posts', [MainController::class, 'posts']);
        Route::get('posts/{post}', [MainController::class, 'showPost']);
        Route::post('clients/{client}/update', [AuthController::class, 'updateProfile']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('toggle-favourite', [MainController::class, 'toggleFavourite'] );
        Route::get('all-favourites', [MainController::class, 'allFavourites'] );
        Route::post('update-notification-settings', [AuthController::class, 'updateNotificationSettings'] );
        Route::get('get-notification-settings', [AuthController::class, 'getNotificationSettings'] );

        


    });

});
