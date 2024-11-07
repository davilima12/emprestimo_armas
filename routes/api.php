<?php

declare(strict_types=1);

use App\Features\Auth\Controllers\AuthController;
use App\Features\User\Controllers\UserController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// Rotas para retornar os produtos
Route::get('/product/{type}', [ProductController::class, 'getProductByType']);
Route::get('/product', [ProductController::class, 'getAll']);

//produtos que foram emprestados
Route::get('/products/loaned', [ProductController::class, 'getLoanedProducts']);

Route::prefix('/auth')
    ->group(function () {
        Route::post('/login', [AuthController::class, 'login']);

        // cadastrar usuario
        Route::post('/create-user', [UserController::class, 'createAccount']);

        Route::prefix('/password')
            ->group(function () {
                //verifica se o token de recuperar senha ainda e valido
                Route::get('/verify-token/{token}', [AuthController::class, 'verifyToken']);
                //rota para alterar senha
                Route::put('/reset/{token}', [AuthController::class, 'resetPassword']);
                // enviar email para alterar senha
                Route::put('/forgot', [AuthController::class, 'sendForgotPasswordEmail']);
            });
    });

Route::middleware('auth')
    ->group(function () {
        Route::prefix('loans')->group(function () {
            Route::get('/', [LoanController::class, 'index']); // Lista todos os empréstimos
            Route::post('/', [LoanController::class, 'store']); // Cria um novo empréstimo
            Route::get('/{id}', [LoanController::class, 'show']); // Mostra um empréstimo específico
            Route::put('/{id}', [LoanController::class, 'update'])->middleware('admin'); // Atualiza um empréstimo existente
            Route::delete('/{id}', [LoanController::class, 'destroy'])->middleware('admin'); // Deleta um empréstimo

            // Rota para devolver produtos
            Route::post('/{loanId}/return', [LoanController::class, 'returnProducts']);

            // Lista todos os empréstimos feitos por um usuario
            Route::get('/user-loans/{user_id}', [LoanController::class, 'loansByUser']);

            // Envia email com os emprestimos por periodo
            Route::get('/send-mail-user-loans/{user_id}', [LoanController::class, 'sendMailLoansByUser']);
        });
    });
