<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\BudgetController;
use App\Http\Controllers\Api\SavingsGoalController;
use App\Http\Controllers\Api\RecurringTransactionController;
use App\Http\Controllers\Api\FinanceNotificationController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    /*
    |---------------------------------------
    | AUTH
    |---------------------------------------
    */
    Route::post('/logout', [AuthController::class, 'logout']);

    /*
    |---------------------------------------
    | USER / PROFILE
    |---------------------------------------
    */
    Route::get('/user', [UserController::class, 'show']);
    Route::put('/user', [UserController::class, 'update']);
    Route::put('/user/password', [UserController::class, 'updatePassword']);

    /*
    |---------------------------------------
    | TRANSACTIONS
    |---------------------------------------
    */
    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::post('/transactions', [TransactionController::class, 'store']);
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show']);
    Route::put('/transactions/{transaction}', [TransactionController::class, 'update']);
    Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy']);

    /*
    |---------------------------------------
    | CATEGORIES
    |---------------------------------------
    */
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{category}', [CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);

    /*
    |---------------------------------------
    | BUDGETS
    |---------------------------------------
    */
    Route::get('/budgets', [BudgetController::class, 'index']);
    Route::post('/budgets', [BudgetController::class, 'store']);
    Route::put('/budgets/{budget}', [BudgetController::class, 'update']);
    Route::delete('/budgets/{budget}', [BudgetController::class, 'destroy']);

    /*
    |---------------------------------------
    | SAVINGS GOALS
    |---------------------------------------
    */
    Route::get('/goals', [SavingsGoalController::class, 'index']);
    Route::post('/goals', [SavingsGoalController::class, 'store']);
    Route::put('/goals/{goal}', [SavingsGoalController::class, 'update']);
    Route::delete('/goals/{goal}', [SavingsGoalController::class, 'destroy']);
    Route::post('/goals/{goal}/deposit', [SavingsGoalController::class, 'deposit']);

    /*
    |---------------------------------------
    | RECURRING TRANSACTIONS
    |---------------------------------------
    */
    Route::get('/recurring-transactions', [RecurringTransactionController::class, 'index']);
    Route::post('/recurring-transactions', [RecurringTransactionController::class, 'store']);
    Route::get('/recurring-transactions/{recurringTransaction}', [RecurringTransactionController::class, 'show']);
    Route::put('/recurring-transactions/{recurringTransaction}', [RecurringTransactionController::class, 'update']);
    Route::delete('/recurring-transactions/{recurringTransaction}', [RecurringTransactionController::class, 'destroy']);

    /*
    |---------------------------------------
    | NOTIFICATIONS
    |---------------------------------------
    */
    Route::get('/notifications', [FinanceNotificationController::class, 'index']);
    Route::post('/notifications', [FinanceNotificationController::class, 'store']);

    Route::put('/notifications/{notification}/read', [FinanceNotificationController::class, 'markAsRead']);
    Route::put('/notifications/read-all', [FinanceNotificationController::class, 'markAllAsRead']);

    Route::delete('/notifications/{notification}', [FinanceNotificationController::class, 'destroy']);
});