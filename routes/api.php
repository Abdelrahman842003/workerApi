<?php

    use App\Http\Controllers\{AdminAuthController, ClientAuthController, WorkerAuthController};

    use Illuminate\Support\Facades\Route;

    // admin aut
    Route::controller(AdminAuthController::class)->group(function () {
        Route::prefix('auth/admin')->group(function () {
            Route::post('/register', 'register');
            Route::post('/login', 'login');

            Route::middleware('authenticated:admin')->group(function () {
                Route::post('/refresh', 'refresh');
                Route::post('/logout', 'logout');
                Route::get('/me', 'me');
            });
        });
    });


    // worker auth
    Route::controller(WorkerAuthController::class)->group(function () {
        Route::prefix('auth/worker')->group(function () {
            Route::post('/register', 'register');
            Route::post('/login', 'login');

            Route::middleware('authenticated:worker')->group(function () {
                Route::post('/refresh', 'refresh');
                Route::post('/logout', 'logout');
                Route::get('/me', 'me');
            });
        });
    });


    // client auth
    Route::controller(ClientAuthController::class)->group(function () {
        Route::prefix('auth/client')->group(function () {
            Route::post('/register', 'register');
            Route::post('/login', 'login');

            Route::middleware('authenticated:client')->group(function () {
                Route::post('/refresh', 'refresh');
                Route::post('/logout', 'logout');
                Route::get('/me', 'me');
            });
        });
    });

