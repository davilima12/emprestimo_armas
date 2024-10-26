<?php

declare(strict_types=1);

use App\Features\Auth\Controllers\AuthController;
use App\Features\Auth\Middleware\AuthenticateMiddleware;
use App\Http\Controllers\LoanController;

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductController;

// Rotas para retornar os produtos
Route::get('/product/{type}', [ProductController::class, 'getProductByType']);
Route::get('/product', [ProductController::class, 'getAll']);

//produtos que foram emprestados
Route::get('/products/loaned', [ProductController::class, 'getLoanedProducts']);

Route::prefix('/auth')
    ->group(function () {
        Route::post('/login', [AuthController::class, 'login']);

        Route::prefix('/password')
            ->group(function () {
                Route::put('/confirmation/{token}', [AuthController::class, 'confirmPassword']);
                Route::put('/reset/{token}', [AuthController::class, 'resetPassword']);
                Route::put('/reset/{token}/verify', [AuthController::class, 'verifyToken']);
                Route::put('/forgot', [AuthController::class, 'sendForgotPasswordEmail']);
            });
    });

Route::middleware(AuthenticateMiddleware::class)
    ->group(function () {
        Route::prefix('loans')->group(function () {
            Route::get('/', [LoanController::class, 'index']); // Lista todos os empréstimos
            Route::post('/', [LoanController::class, 'store']); // Cria um novo empréstimo
            Route::get('/{id}', [LoanController::class, 'show']); // Mostra um empréstimo específico
            Route::put('/{id}', [LoanController::class, 'update']); // Atualiza um empréstimo existente
            Route::delete('/{id}', [LoanController::class, 'destroy']); // Deleta um empréstimo

            // Rota para devolver produtos
            Route::post('/{loanId}/return', [LoanController::class, 'returnProducts']);
        });

    });
