<?php
use App\Http\Controllers\ProfessorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('/professores',ProfessorController::class);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
