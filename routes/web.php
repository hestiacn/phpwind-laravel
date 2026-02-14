<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\AboutMourningController;

// 首页 - 使用控制器（传递主题数据到欢迎页）
Route::get('/', [HomeController::class, 'index'])->name('home');

// 关于悼念页面
Route::get('/about/mourning', [AboutMourningController::class, 'index'])->name('about.mourning');

// 主题切换 API
Route::post('/api/theme', [ThemeController::class, 'update'])->name('api.theme');