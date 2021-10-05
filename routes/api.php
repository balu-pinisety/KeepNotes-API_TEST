<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\LabelController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);

    Route::post('forgotpassword', [ForgotPasswordController::class, 'forgotPassword']);
    Route::post('resetpassword', [ForgotPasswordController::class, 'resetPassword']);

    Route::post('createnote', [NoteController::class, 'createNote']);
    Route::post('displaynote', [NoteController::class, 'displayNoteById']);
    Route::post('deletenote', [NoteController::class, 'deleteNoteById']);
    Route::put('updatenote', [NoteController::class, 'updateNoteById']);
    Route::get('fetchnotes', [NoteController::class, 'getAllNotes']);
    
    Route::post('createlabel', [LabelController::class, 'createLabel']);
    Route::post('displaylabel', [LabelController::class, 'displayLabelById']);
    Route::put('updatelabel', [LabelController::class, 'updateLabelById']);
    Route::post('deletelabel', [LabelController::class, 'deleteLabelById']);
    Route::get('fetchlabels', [LabelController::class, 'getAllLabels']);
    Route::get('notesByLabel', [LabelController::class, 'getNotesByLabel']);
   
}); 