<?php

namespace App\Http\Controllers;

use App\Services\ThemeService;
use App\Services\HolidayService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $themeService;
    protected $holidayService;

    public function __construct(ThemeService $themeService, HolidayService $holidayService)
    {
        $this->themeService = $themeService;
        $this->holidayService = $holidayService;
    }

    public function index()
    {
        // 获取用户主题设置
        $userTheme = auth()->check() ? auth()->user()->theme_preference : null;
        $userTimezone = auth()->check() ? auth()->user()->timezone : null;
        
        // 获取主题数据
        $themeData = $this->themeService->getThemeData($userTheme, $userTimezone);
        
        // 获取节日数据
        $holidayData = $this->holidayService->getHolidayData($userTimezone);
        
        return view('home', compact('themeData', 'holidayData'));
    }
}