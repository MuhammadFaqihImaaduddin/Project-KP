<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApprovalController;

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

    // Get submissions based on area kerja
    Route::get('/approval/area/{areaKerjaId}', [ApprovalController::class, 'getSubmissionsByArea']);

    // Approve submission
    Route::post('/approval/approve/{submissionId}', [ApprovalController::class, 'approveSubmission']);

    // Reject submission
    Route::post('/approval/reject/{submissionId}', [ApprovalController::class, 'rejectSubmission']);


    return $request->user();
});


