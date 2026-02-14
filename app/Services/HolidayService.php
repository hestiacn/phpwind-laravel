<?php
// app/Services/HolidayService.php

namespace App\Services;

use Carbon\Carbon;
use App\Services\LunarService;

class HolidayService
{
    protected $lunarService;
    
    // 公历节日
    protected $solarHolidays = [
        '01-01' => 'new_year',
        '02-14' => 'valentine',
        '03-08' => 'womens_day',
        '05-01' => 'labour_day',
        '06-01' => 'children_day',
        '07-01' => 'party_day',
        '08-01' => 'army_day',
        '09-10' => 'teachers_day',
        '10-01' => 'national_day',
        '12-25' => 'christmas',
    ];

    // 哀悼日
    protected $mourningDays = [
        '05-12' => ['name' => 'mourning_512', 'icon' => '🕯️'],
        '12-13' => ['name' => 'mourning_1213', 'icon' => '🕯️'],
    ];

    public function __construct(LunarService $lunarService)
    {
        $this->lunarService = $lunarService;
    }

	// 1. 农历节日
	public function getCurrentHoliday($timezone = 'Asia/Shanghai')
	{
	    $now = Carbon::now($timezone);
	    $dateKey = $now->format('m-d');
	    
	    // 1. 农历节日 - 修复版本
	    $lunar = $this->lunarService->getLunarDate($now);
	    $lunarKey = sprintf('%02d-%02d', $lunar['month'], $lunar['day']);
	    
	    // 通过公共方法获取农历节日
	    $lunarHolidays = $this->lunarService->getLunarHolidays();
	    if (isset($lunarHolidays[$lunarKey])) {
	        $holiday = $lunarHolidays[$lunarKey];
	        return [
	            'key' => $holiday['key'],
	            'type' => 'lunar',
	            'icon' => $this->getHolidayIcon($holiday['key']),
	            'mourning' => $holiday['mourning'] ?? false,
	            'lunar' => $lunar,
	        ];
	    }
	
	    // 2. 二十四节气
	    $currentSolarTerm = $this->lunarService->getCurrentSolarTerm($now);
	    if ($currentSolarTerm) {
	        $termInfo = $this->lunarService->getSolarTerm($currentSolarTerm);
	        return [
	            'key' => $currentSolarTerm,
	            'type' => 'solar_term',
	            'icon' => $this->getSolarTermIcon($currentSolarTerm),
	            'mourning' => $termInfo['mourning'] ?? false,
	        ];
	    }
	    
	    // 3. 哀悼日
	    if (isset($this->mourningDays[$dateKey])) {
	        $mourning = $this->mourningDays[$dateKey];
	        return [
	            'key' => $mourning['name'],
	            'type' => 'mourning',
	            'icon' => $mourning['icon'],
	            'mourning' => true,
	        ];
	    }
	    
	    // 4. 公历节日
	    if (isset($this->solarHolidays[$dateKey])) {
	        return [
	            'key' => $this->solarHolidays[$dateKey],
	            'type' => 'solar',
	            'icon' => $this->getHolidayIcon($this->solarHolidays[$dateKey]),
	            'mourning' => false,
	        ];
	    }
	    
	    return null;
	}

    protected function getHolidayIcon($key)
    {
        $icons = [
            'new_year' => '🎉',
            'valentine' => '❤️',
            'womens_day' => '🌸',
            'labour_day' => '⚒️',
            'children_day' => '🍭',
            'party_day' => '🎂',
            'army_day' => '🎖️',
            'teachers_day' => '📚',
            'national_day' => '🎊',
            'christmas' => '🎄',
            'spring_festival' => '🧧',
            'lantern_festival' => '🏮',
            'dragon_head' => '🐲',
            'dragon_boat' => '🛶',
            'qixi' => '💑',
            'ghost_festival' => '🕯️',
            'mid_autumn' => '🌕',
            'double_ninth' => '🏔️',
            'laba' => '🥣',
            'xiao_nian' => '🧹',
            'new_year_eve' => '🎆',
            'mourning_512' => '🕯️',
            'mourning_1213' => '🕯️',
        ];
        return $icons[$key] ?? '🎉';
    }

    protected function getSolarTermIcon($key)
    {
        $icons = [
            'beginning_of_spring' => '🌱',
            'rain_water' => '💧',
            'awakening_of_insects' => '🐞',
            'spring_equinox' => '⚖️',
            'qingming' => '🌧️',
            'grain_rain' => '🌾',
            'beginning_of_summer' => '☀️',
            'grain_buds' => '🌽',
            'grain_in_ear' => '🌾',
            'summer_solstice' => '🌞',
            'minor_heat' => '🔥',
            'major_heat' => '🌋',
            'beginning_of_autumn' => '🍂',
            'end_of_heat' => '🍁',
            'white_dew' => '💧',
            'autumn_equinox' => '⚖️',
            'cold_dew' => '❄️',
            'frost_descent' => '❄️',
            'beginning_of_winter' => '⛄',
            'minor_snow' => '❄️',
            'major_snow' => '❄️',
            'winter_solstice' => '🥟',
            'minor_cold' => '⛄',
            'major_cold' => '❄️',
        ];
        return $icons[$key] ?? '📅';
    }

    public function getHolidayData($timezone = 'Asia/Shanghai')
    {
        $holiday = $this->getCurrentHoliday($timezone);
        $lunar = $this->lunarService->getLunarDate(Carbon::now($timezone));
        
        return [
            'has_holiday' => !is_null($holiday),
            'holiday' => $holiday,
            'lunar' => $lunar,
            'mourning' => $holiday['mourning'] ?? false,
            'theme_class' => $holiday ? 'holiday-' . str_replace('_', '-', $holiday['key']) : '',
        ];
    }
}