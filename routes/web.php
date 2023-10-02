<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::post('telegram/index', [\App\Http\Controllers\TelegramController::class, 'index'])->name('telegram.index');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::group(['middleware' => ['role:admin']], function () {
        Route::resource('start', \App\Http\Controllers\StartController::class);
        Route::resource('question', \App\Http\Controllers\QuestionController::class);
        Route::post('question/create-button', [\App\Http\Controllers\QuestionController::class, 'createButton'])->name('question.createButton');
        Route::resource('category', \App\Http\Controllers\CategoryController::class);
        Route::resource('user', \App\Http\Controllers\UserController::class);
        Route::resource('region', \App\Http\Controllers\RegionController::class);
        Route::resource('message', \App\Http\Controllers\MessageController::class);
        Route::get('log', [\App\Http\Controllers\LogController::class, 'index'])->name('log.index');
        Route::resource('supplier-assignment', \App\Http\Controllers\SupplierAssignmentController::class);
        Route::get('supplier-assignment/trash/index', [\App\Http\Controllers\SupplierAssignmentController::class, 'trashed'])->name('supplier-assignment.trashed');
        Route::get('supplier-assignment/closed/index', [\App\Http\Controllers\SupplierAssignmentController::class, 'closedIndex'])->name('supplier-assignment.closedIndex');
        Route::get('supplier-assignment/{user_id}/car', [\App\Http\Controllers\SupplierAssignmentController::class, 'getCarByUser'])->name('supplier-assignment.car');

        Route::post('send-notification-to-supplier', [\App\Http\Controllers\Api\SupplierNotificationController::class, 'send'])->name('send-notification-to-supplier');

        Route::resource('car', \App\Http\Controllers\CarController::class)->except('show');
        Route::patch('car/{car_id}/hybrid-sleep', [\App\Http\Controllers\CarController::class, 'hybridSleep'])->name('car.hybrid-sleep');

        Route::resource('additional-notice', \App\Http\Controllers\AdditionalNoticeController::class);
    });
    Route::group(['middleware' => ['role:manager|admin']], function () {

        Route::get('answer/{application_id}', [\App\Http\Controllers\AnswerController::class, 'show'])->name('answer.show');
        Route::get('answer/{application_id}/createPDF', [\App\Http\Controllers\AnswerController::class, 'createPDF'])->name('answer.pdf');

        Route::get('answer/manager/index', [\App\Http\Controllers\AnswerController::class, 'managerIndex'])->name('answer.managerIndex');
        Route::get('answer/manager/{application_id}/accepted', [\App\Http\Controllers\AnswerController::class, 'managerAccepted'])->name('answer.managerAccepted');
        Route::post('answer/manager/{application_id}/accepted', [\App\Http\Controllers\AnswerController::class, 'managerAccepted']);
        Route::get('answer/manager/{application_id}/cancel', [\App\Http\Controllers\AnswerController::class, 'managerCancel'])->name('answer.managerCancel');
        Route::post('answer/manager/{application_id}/cancel', [\App\Http\Controllers\AnswerController::class, 'managerCancel']);
        Route::get('answer/manager/accepted/index', [\App\Http\Controllers\AnswerController::class, 'managerAcceptedIndex'])->name('answer.managerAcceptedIndex');
        Route::get('answer/manager/cancel/index', [\App\Http\Controllers\AnswerController::class, 'managerCancelIndex'])->name('answer.managerCancelIndex');

    });

    Route::group(['middleware' => ['role:contract|admin']], function () {

        Route::get('answer/{application_id}', [\App\Http\Controllers\AnswerController::class, 'show'])->name('answer.show');
        Route::get('answer/{application_id}/createPDF', [\App\Http\Controllers\AnswerController::class, 'createPDF'])->name('answer.pdf');

        Route::get('answer/contract/index', [\App\Http\Controllers\AnswerController::class, 'contractIndex'])->name('answer.contractIndex');
        Route::get('answer/contract/{application_id}/accepted', [\App\Http\Controllers\AnswerController::class, 'contractAccepted'])->name('answer.contractAccepted');
        Route::post('answer/contract/{application_id}/accepted', [\App\Http\Controllers\AnswerController::class, 'contractAccepted']);
        Route::get('answer/contract/{application_id}/cancel', [\App\Http\Controllers\AnswerController::class, 'contractCancel'])->name('answer.contractCancel');
        Route::post('answer/contract/{application_id}/cancel', [\App\Http\Controllers\AnswerController::class, 'contractCancel']);
        Route::get('answer/contract/accepted/index', [\App\Http\Controllers\AnswerController::class, 'contractAcceptedIndex'])->name('answer.contractAcceptedIndex');
        Route::get('answer/contract/cancel/index', [\App\Http\Controllers\AnswerController::class, 'contractCancelIndex'])->name('answer.contractCancelIndex');

        Route::resource('ticket', \App\Http\Controllers\TicketController::class);
        Route::get('ticket/accepted/index', [\App\Http\Controllers\TicketController::class, 'acceptedIndex'])->name('ticket.acceptedIndex');
        Route::get('ticket/closed/index', [\App\Http\Controllers\TicketController::class, 'closedIndex'])->name('ticket.closedIndex');
        Route::get('ticket/trash/index', [\App\Http\Controllers\TicketController::class, 'trashIndex'])->name('ticket.trashIndex');
        Route::post('ticket/add-product', [\App\Http\Controllers\TicketController::class, 'addProduct'])->name('ticket.addProduct');
        Route::get('ticket/{user_id}/car', [\App\Http\Controllers\TicketController::class, 'getCarByUser'])->name('ticket.car');
        Route::get('ticket/active/list', [\App\Http\Controllers\TicketController::class, 'getActiveList'])->name('ticket.activeList');
    });
});

require __DIR__.'/auth.php';
