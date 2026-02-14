<?php

namespace App\Http\Controllers;

use App\Services\HolidayService;
use App\Services\ThemeService;
use Illuminate\Http\Request;

class AboutMourningController extends Controller
{
    protected $holidayService;
    protected $themeService;

    public function __construct(HolidayService $holidayService, ThemeService $themeService)
    {
        $this->holidayService = $holidayService;
        $this->themeService = $themeService;
    }

    public function index()
    {
        $holiday = $this->holidayService->getCurrentHoliday();
        
        // 获取主题数据
        $userTheme = auth()->check() ? auth()->user()->theme_preference : null;
        $userTimezone = auth()->check() ? auth()->user()->timezone : null;
        $themeData = $this->themeService->getThemeData($userTheme, $userTimezone);
        
        return view('about.mourning', [
            'holidayKey' => $holiday['key'] ?? 'mourning_512',
            'themeData' => $themeData
        ]);
    }
}