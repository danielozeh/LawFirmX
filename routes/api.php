<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ClientController;
use App\Http\Controllers\CaseController;
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



/**
 * @author Daniel Ozeh hello@danielozeh.com.ng
 */




//////////////////////////////////////////////////////////////
/////////////////CLIENT ROUTES ////////////////////////////////
////////////////////////////////////////////
Route::group([
    'prefix' => 'client'

],  function($router) {
    Route::get('/all', [ClientController::class, 'getAllClients']);
    Route::get('/profile/{id}', [ClientController::class, 'getClientInfo']);
    Route::get('/cases/{id}', [ClientController::class, 'getClientCases']);
    Route::post('/search-by-lastname', [ClientController::class, 'searchByLastName']);
    Route::post('/update-profile-picture/{id}', [ClientController::class, 'updateProfilePicture']);
    Route::get('test-mail', [ClientController::class, 'notifyClientWithNullProfile']);
});


/////////////////////////////////////////////////////////////
//////////CASE ROUTES///////////////////////////
/////////////////////////////////////////////////////////
Route::group([
    'prefix' => 'case'

],  function($router) {
    Route::post('/add-type', [CaseController::class, 'addCaseType']);
    Route::put('/edit-type/{id}', [CaseController::class, 'editCaseType']);
    Route::delete('/delete-type/{id}', [CaseController::class, 'deleteCaseType']);

    Route::get('/type/all-cases/{id}', [CaseController::class, 'getCasesByCaseType']);
    Route::get('/type/all', [CaseController::class, 'getAllCaseType']);

    Route::post('/add-case', [CaseController::class, 'addCaseDetails']);
    Route::put('/edit-case/{id}', [CaseController::class, 'editCaseDetails']);
    Route::delete('/delete-case/{id}', [CaseController::class, 'deleteCaseDetails']);
    Route::put('/update-stage/{id}', [CaseController::class, 'updateCaseStage']);
    Route::get('/all', [CaseController::class, 'getAllCase']);
});
