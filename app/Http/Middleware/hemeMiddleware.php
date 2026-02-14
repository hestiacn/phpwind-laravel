<?php
// app/Http/Middleware/ThemeMiddleware.php

namespace App\Http\Middleware;

use Closure;
use App\Services\ThemeService;
use App\Services\HolidayService;

class ThemeMiddleware
{
    protected $themeService;
    protected $holidayService;

    public function __construct(ThemeService $themeService, HolidayService $holidayService)
    {
        $this->themeService = $themeService;
        $this->holidayService = $holidayService;
    }

    public function handle($request, Closure $next)
    {
        // 主题切换
        if ($request->has('theme')) {
            $theme = $request->input('theme');
            if (in_array($theme, ['light', 'dark', 'auto'])) {
                session(['theme' => $theme]);
            }
        }

        // 时区
        $timezone = $request->cookie('user_timezone', session('timezone', 'Asia/Shanghai'));
        session(['timezone' => $timezone]);

        // 获取数据
        $themeData = $this->themeService->getThemeData(session('theme'), $timezone);
        $holidayData = $this->holidayService->getHolidayData($timezone);
        
        view()->share('themeData', $themeData);
        view()->share('holidayData', $holidayData);
        
        return $next($request);
    }
}