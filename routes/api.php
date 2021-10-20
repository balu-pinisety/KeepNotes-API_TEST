<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\CollabaratorController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

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
    Route::post('addlabel', [LabelController::class, 'addLabel']);
    Route::post('displaylabel', [LabelController::class, 'displayLabelById']);
    Route::put('updatelabel', [LabelController::class, 'updateLabelById']);
    Route::post('deletelabel', [LabelController::class, 'deleteLabelById']);
    Route::get('fetchlabels', [LabelController::class, 'getAllLabels']);
    Route::get('labelByNote', [LabelController::class, 'getLabelsByNote']);

    Route::get('noteLabel', [NoteController::class, 'getAllNotesLabels']);

    Route::post('pin', [NoteController::class, 'pinNote']);
    Route::get('allpins', [NoteController::class, 'getAllPins']);

    Route::post('archive', [NoteController::class, 'archiveNote']);
    Route::get('allarchives', [NoteController::class, 'getAllArchive']);

    Route::post('colour', [NoteController::class, 'colourNote']);
    Route::get('ColouredNotes', [NoteController::class, 'getColouredNotes']);

    Route::get('paginatenote', [NoteController::class, 'getpaginateNoteData']);

    Route::post('addcollabarator', [CollabaratorController::class, 'addCollabatorByNoteId']);
    Route::put('updateNotecollabarator', [CollabaratorController::class, 'updateNoteByCollabarator']);
    Route::post('deletecollabarator', [CollabaratorController::class, 'deleteCollabaratorNote']);
    Route::get('allCollabarataors', [CollabaratorController::class, 'getAllCollabarators']);

    Route::get('search', [NoteController::class, 'getSearch']);

}); 

Route::fallback(function(){
    return response()->json([
        'message' => 'Page Not Found. If error persists, check the Route URL'], 404);
});