// routes/api.php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ThemeController;

Route::post('/theme', [ThemeController::class, 'switch']);
Route::get('/theme', [ThemeController::class, 'current']);