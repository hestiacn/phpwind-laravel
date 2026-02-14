<?php

namespace App\Services;

use Carbon\Carbon;

class LunarService
{
    /**
     * 从1900年到2200年的农历月份数据代码
     * 数据范围：1900-2200年（共301年）
     */
    protected $currentYear;
    private const CHINESE_YEAR_CODE = [
        // 1900-1909
        19416, 19168, 42352, 21717, 53856, 55632, 91476, 22176, 39632, 21970,
        // 1910-1919
        19168, 42422, 42192, 53840, 119381, 46400, 54944, 44450, 38320, 84343,
        // 1920-1929
        18800, 42160, 46261, 27216, 27968, 109396, 11104, 38256, 21234, 18800,
        // 1930-1939
        18800, 25776, 92326, 59984, 27296, 108228, 43744, 37600, 53987, 51552,
        // 1940-1949
        54615, 54432, 55888, 23893, 22176, 42704, 21972, 21200, 43448, 43344,
        // 1950-1959
        46240, 46758, 44368, 21920, 43940, 42416, 21168, 45683, 26928, 29495,
        // 1960-1969
        27296, 44368, 84821, 19296, 42352, 21732, 53600, 59752, 54560, 55968,
        // 1970-1979
        92838, 22224, 19168, 43476, 41680, 53584, 62034, 54560, 46357, 48800,
        // 1980-1989
        53600, 47732, 22224, 21936, 96848, 59984, 27296, 44368, 23378, 19296,
        // 1990-1999
        42726, 42208, 53856, 60005, 54576, 23200, 30371, 38608, 19195, 19152,
        // 2000-2009
        42192, 118966, 53840, 54560, 56645, 46496, 22224, 21938, 18864, 42359,
        // 2010-2019
        42160, 43600, 111189, 27936, 44448, 84835, 37744, 18936, 18800, 25776,
        // 2020-2029
        92326, 59984, 27296, 108228, 43744, 37600, 53987, 51552, 54615, 54432,
        // 2030-2039
        55888, 23893, 22176, 42704, 21972, 21200, 43448, 43344, 46240, 46758,
        // 2040-2049
        44368, 21920, 43940, 42416, 21168, 45683, 26928, 29495, 27296, 44368,
        // 2050-2059
        84821, 19296, 42352, 21732, 53600, 59752, 54560, 55968, 92838, 22224,
        // 2060-2069
        19168, 43476, 41680, 53584, 62034, 54560, 46357, 48800, 53600, 47732,
        // 2070-2079
        22224, 21936, 96848, 59984, 27296, 44368, 23378, 19296, 42726, 42208,
        // 2080-2089
        53856, 60005, 54576, 23200, 30371, 38608, 19195, 19152, 42192, 118966,
        // 2090-2099
        53840, 54560, 56645, 46496, 22224, 21938, 18864, 42359, 42160, 43600,
        // 2100-2109
        111189, 27936, 44448, 84835, 37744, 18936, 18800, 25776, 92326, 59984,
        // 2110-2119
        27296, 108228, 43744, 37600, 53987, 51552, 54615, 54432, 55888, 23893,
        // 2120-2129
        22176, 42704, 21972, 21200, 43448, 43344, 46240, 46758, 44368, 21920,
        // 2130-2139
        43940, 42416, 21168, 45683, 26928, 29495, 27296, 44368, 84821, 19296,
        // 2140-2149
        42352, 21732, 53600, 59752, 54560, 55968, 92838, 22224, 19168, 43476,
        // 2150-2159
        41680, 53584, 62034, 54560, 46357, 48800, 53600, 47732, 22224, 21936,
        // 2160-2169
        96848, 59984, 27296, 44368, 23378, 19296, 42726, 42208, 53856, 60005,
        // 2170-2179
        54576, 23200, 30371, 38608, 19195, 19152, 42192, 118966, 53840, 54560,
        // 2180-2189
        56645, 46496, 22224, 21938, 18864, 42359, 42160, 43600, 111189, 27936,
        // 2190-2199
        44448, 84835, 37744, 18936, 18800, 25776, 92326, 59984, 27296, 108228,
        // 2200
        43744
    ];

    /**
     * 从1900年至2200年每年的农历春节的公历日期 (YYYYMMDD格式)
     */
    private const CHINESE_NEW_YEAR_DATE = [
        // 1900-1909
        19010131, 19020219, 19030208, 19040129, 19050216, 19060204, 19070125, 19080213, 19090202, 19100221,
        // 1910-1919
        19110210, 19120130, 19130218, 19140206, 19150125, 19160214, 19170202, 19180211, 19190201, 19200220,
        // 1920-1929
        19210208, 19220128, 19230216, 19240205, 19250124, 19260213, 19270202, 19280223, 19290210, 19300130,
        // 1930-1939
        19310217, 19320206, 19330126, 19340214, 19350204, 19360124, 19370211, 19380131, 19390219, 19400208,
        // 1940-1949
        19410127, 19420215, 19430205, 19440125, 19450213, 19460202, 19470122, 19480210, 19490129, 19500217,
        // 1950-1959
        19510206, 19520127, 19530214, 19540203, 19550224, 19560212, 19570131, 19580218, 19590208, 19600128,
        // 1960-1969
        19610215, 19620205, 19630125, 19640213, 19650202, 19660121, 19670209, 19680130, 19690217, 19700206,
        // 1970-1979
        19710127, 19720215, 19730203, 19740123, 19750211, 19760131, 19770218, 19780207, 19790128, 19800216,
        // 1980-1989
        19810205, 19820125, 19830213, 19840202, 19850220, 19860209, 19870129, 19880217, 19890206, 19900127,
        // 1990-1999
        19910215, 19920204, 19930123, 19940210, 19950131, 19960219, 19970207, 19980128, 19990216, 20000205,
        // 2000-2009
        20010124, 20020212, 20030201, 20040122, 20050209, 20060129, 20070218, 20080207, 20090126, 20100214,
        // 2010-2019
        20110203, 20120123, 20130210, 20140131, 20150219, 20160208, 20170128, 20180216, 20190205, 20200125,
        // 2020-2029
        20210212, 20220201, 20230122, 20240210, 20250129, 20260217, 20270206, 20280126, 20290213, 20300203,
        // 2030-2039
        20310123, 20320211, 20330131, 20340219, 20350208, 20360128, 20370215, 20380204, 20390124, 20400212,
        // 2040-2049
        20410201, 20420122, 20430210, 20440130, 20450217, 20460206, 20470126, 20480214, 20490202, 20500123,
        // 2050-2059
        20510211, 20520201, 20530219, 20540208, 20550128, 20560215, 20570204, 20580124, 20590212, 20600202,
        // 2060-2069
        20610121, 20620209, 20630129, 20640217, 20650205, 20660126, 20670214, 20680203, 20690123, 20700211,
        // 2070-2079
        20710131, 20720219, 20730207, 20740127, 20750215, 20760205, 20770124, 20780212, 20790202, 20800122,
        // 2080-2089
        20810209, 20820129, 20830217, 20840206, 20850126, 20860214, 20870203, 20880124, 20890210, 20900130,
        // 2090-2099
        20910218, 20920207, 20930127, 20940215, 20950205, 20960125, 20970212, 20980201, 20990121, 21000209,
        // 2100-2109
        21010129, 21020218, 21030208, 21040129, 21050217, 21060205, 21070125, 21080213, 21090203, 21100220,
        // 2110-2119
        21110210, 21120130, 21130218, 21140207, 21150127, 21160215, 21170204, 21180125, 21190213, 21200202,
        // 2120-2129
        21210221, 21220210, 21230130, 21240218, 21250207, 21260126, 21270214, 21280203, 21290123, 21300211,
        // 2130-2139
        21310131, 21320219, 21330208, 21340129, 21350217, 21360205, 21370125, 21380213, 21390202, 21400220,
        // 2140-2149
        21410209, 21420130, 21430219, 21440208, 21450128, 21460217, 21470206, 21480126, 21490214, 21500204,
        // 2150-2159
        21510224, 21520212, 21530202, 21540221, 21550210, 21560130, 21570218, 21580207, 21590127, 21600215,
        // 2160-2169
        21610205, 21620125, 21630214, 21640203, 21650222, 21660211, 21670131, 21680219, 21690208, 21700129,
        // 2170-2179
        21710217, 21720206, 21730126, 21740214, 21750203, 21760123, 21770211, 21780201, 21790220, 21800209,
        // 2180-2189
        21810128, 21820217, 21830206, 21840126, 21850214, 21860203, 21870123, 21880211, 21890131, 21900219,
        // 2190-2199
        21910208, 21920128, 21930216, 21940205, 21950125, 21960213, 21970202, 21980222, 21990212, 22000202,
        // 2200
        22000202
    ];

    private const ZHNUMS = ["零", "一", "二", "三", "四", "五", "六", "七", "八", "九", "十"];
    private const TIAN_GAN = ["甲", "乙", "丙", "丁", "戊", "己", "庚", "辛", "壬", "癸"];
    private const DI_ZHI = ["子", "丑", "寅", "卯", "辰", "巳", "午", "未", "申", "酉", "戌", "亥"];
    private const SHENG_XIAO = ["鼠", "牛", "虎", "兔", "龙", "蛇", "马", "羊", "猴", "鸡", "狗", "猪"];

    protected $lunarHolidays = [
        '01-01' => ['key' => 'spring_festival', 'days' => 15],
        '01-15' => ['key' => 'lantern_festival'],
        '02-02' => ['key' => 'dragon_head'],
        '05-05' => ['key' => 'dragon_boat'],
        '07-07' => ['key' => 'qixi'],
        '07-15' => ['key' => 'ghost_festival', 'mourning' => true],
        '08-15' => ['key' => 'mid_autumn'],
        '09-09' => ['key' => 'double_ninth'],
        '12-08' => ['key' => 'laba'],
        '12-23' => ['key' => 'xiao_nian'],
        '12-30' => ['key' => 'new_year_eve'],
    ];

    protected $solarTerms = [
        'beginning_of_spring' => ['month' => 2, 'day' => 4],
        'rain_water' => ['month' => 2, 'day' => 19],
        'awakening_of_insects' => ['month' => 3, 'day' => 6],
        'spring_equinox' => ['month' => 3, 'day' => 20],
        'qingming' => ['month' => 4, 'day' => 5, 'mourning' => true],
        'grain_rain' => ['month' => 4, 'day' => 20],
        'beginning_of_summer' => ['month' => 5, 'day' => 6],
        'grain_buds' => ['month' => 5, 'day' => 21],
        'grain_in_ear' => ['month' => 6, 'day' => 6],
        'summer_solstice' => ['month' => 6, 'day' => 21],
        'minor_heat' => ['month' => 7, 'day' => 7],
        'major_heat' => ['month' => 7, 'day' => 23],
        'beginning_of_autumn' => ['month' => 8, 'day' => 8],
        'end_of_heat' => ['month' => 8, 'day' => 23],
        'white_dew' => ['month' => 9, 'day' => 8],
        'autumn_equinox' => ['month' => 9, 'day' => 23],
        'cold_dew' => ['month' => 10, 'day' => 8],
        'frost_descent' => ['month' => 10, 'day' => 24],
        'beginning_of_winter' => ['month' => 11, 'day' => 7],
        'minor_snow' => ['month' => 11, 'day' => 22],
        'major_snow' => ['month' => 12, 'day' => 7],
        'winter_solstice' => ['month' => 12, 'day' => 22],
        'minor_cold' => ['month' => 1, 'day' => 6],
        'major_cold' => ['month' => 1, 'day' => 20],
    ];

    /**
     * 获取公历对应的农历日期（使用 Carbon）
     */
    public function getLunarDate($date = null): array
    {
        $carbon = $date ? Carbon::parse($date) : Carbon::now();
        $year = $carbon->year;
        $month = $carbon->month;
        $day = $carbon->day;
	   $this->currentYear = $year;
        // 验证年份范围
        if ($year < 1900 || $year > 2200) {
            throw new \InvalidArgumentException("年份必须在1900-2200范围内");
        }

        // 确定农历年
        $lunarYear = $this->determineLunarYear($year, $month, $day);
        
        // 计算从春节到目标日期的天数
        $daysPassed = $this->calculateDaysPassed($lunarYear, $year, $month, $day);
        
        // 解析年度代码获取每月天数
        $yearCode = self::CHINESE_YEAR_CODE[$lunarYear - 1900];
        $monthDays = $this->decode($yearCode);
        
        // 计算农历月和日
        [$lunarMonth, $lunarDay, $leapMonth] = $this->calculateLunarMonthDay($yearCode, $monthDays, $daysPassed);
	    if ($lunarDay < 1) {
	        $lunarDay = 1;
	    }
        return [
            'year' => $lunarYear,
            'month' => $lunarMonth,
            'day' => $lunarDay,
            'leap' => $leapMonth,
            'year_name' => $this->getYearName($lunarYear),
            'month_name' => $this->getMonthName($lunarMonth, $leapMonth),
            'day_name' => $this->getDayName($lunarDay),
            'shengxiao' => $this->getShengXiao($lunarYear),
            'tiandi' => $this->getTianDi($lunarYear),
            'full_string' => $this->formatFullString($lunarYear, $lunarMonth, $lunarDay, $leapMonth),
        ];
    }

    /**
     * 确定农历年（处理公历年初的农历跨年问题）
     */
    private function determineLunarYear(int $year, int $month, int $day): int
    {
        $lunarYear = $year;
        
        // 获取当年春节日期
        $newYearDate = self::CHINESE_NEW_YEAR_DATE[$year - 1900];
        $newYearY = (int)($newYearDate / 10000);
        $newYearM = (int)(($newYearDate / 100) % 100);
        $newYearD = $newYearDate % 100;
        
        // 如果在春节之前，则属于上一年农历
        if ($newYearY > $year || 
            ($newYearY == $year && $newYearM > $month) ||
            ($newYearY == $year && $newYearM == $month && $newYearD > $day)) {
            $lunarYear--;
        }
        
        return $lunarYear;
    }

    /**
     * 计算从春节到目标日期的天数
     */
    private function calculateDaysPassed(int $lunarYear, int $year, int $month, int $day): int
    {
        $newYearDate = self::CHINESE_NEW_YEAR_DATE[$lunarYear - 1900];
        $newYearY = (int)($newYearDate / 10000);
        $newYearM = (int)(($newYearDate / 100) % 100);
        $newYearD = $newYearDate % 100;
        
        $newYear = Carbon::create($newYearY, $newYearM, $newYearD);
        $targetDate = Carbon::create($year, $month, $day);
        
        return $newYear->diffInDays($targetDate);
    }

    /**
     * 解析年度代码，获取每月天数数组
     */
    private function decode(int $yearCode): array
    {
        $runYue = $yearCode & 0xf; // 闰月月份
        $monthDays = [];
        
        if ($runYue > 0) {
            // 有闰月，13个月
            $monthDays = array_fill(0, 14, 0);
            
            // 设置闰月天数
            $monthDays[$runYue + 1] = (($yearCode >> 16) & 1) ? 30 : 29;
            
            // 填充正常月份
            $normalMonthIndex = 1;
            for ($i = 5; $i < 17; $i++) {
                $isBigMonth = (($yearCode >> ($i - 1)) & 1) == 1;
                $targetMonth = 17 - $i;
                
                if ($targetMonth == $runYue) {
                    $monthDays[$normalMonthIndex] = $isBigMonth ? 30 : 29;
                    $normalMonthIndex++;
                    $monthDays[$normalMonthIndex] = $monthDays[$runYue + 1];
                    $normalMonthIndex++;
                } else {
                    $monthDays[$normalMonthIndex] = $isBigMonth ? 30 : 29;
                    $normalMonthIndex++;
                }
            }
            
            // 重新索引
            $temp = [];
            for ($i = 1; $i <= 13; $i++) {
                $temp[] = $monthDays[$i];
            }
            $monthDays = $temp;
        } else {
            // 无闰月，12个月
            $monthDays = array_fill(0, 12, 0);
            
            for ($i = 5; $i < 17; $i++) {
                $isBigMonth = (($yearCode >> ($i - 1)) & 1) == 1;
                $monthIndex = 17 - $i - 1;
                
                if ($monthIndex >= 0 && $monthIndex < 12) {
                    $monthDays[$monthIndex] = $isBigMonth ? 30 : 29;
                }
            }
        }
        
        return $monthDays;
    }

	/**
	 * 计算农历月和日（修复版）
	 */
	private function calculateLunarMonthDay(int $yearCode, array $monthDays, int $daysPassed): array
	{
	    $runYue = $yearCode & 0xf; // 闰月月份
	    $acc = $this->accumulate($monthDays);
	    $size = count($monthDays);
	    
	    $lunarMonth = 1;
	    $lunarDay = 1;
	    $leapMonth = false;
	    
	    // 如果天数小于0，说明在春节之前，属于上一年农历十二月
	    if ($daysPassed < 0) {
	        // 获取上一年的年份代码
	        // 假设当前年份是2026，上一年是2025
	        $prevYearIndex = ($this->currentYear ?? 2026) - 1901;
	        
	        // 确保索引在有效范围内
	        if ($prevYearIndex < 0 || $prevYearIndex >= count(self::CHINESE_YEAR_CODE)) {
	            $prevYearCode = self::CHINESE_YEAR_CODE[0]; // 默认使用1900年
	        } else {
	            $prevYearCode = self::CHINESE_YEAR_CODE[$prevYearIndex];
	        }
	        
	        $prevMonthDays = $this->decode($prevYearCode);
	        
	        // 计算农历日期（在春节之前，属于上一年腊月）
	        $daysFromPrevYearEnd = abs($daysPassed);
	        $lunarMonth = 12; // 腊月
	        // 腊月的天数
	        $lastMonthDays = $prevMonthDays[11] ?? 30;
	        $lunarDay = $lastMonthDays - $daysFromPrevYearEnd + 1;
	        
	        // 确保农历日在有效范围内
	        if ($lunarDay < 1) {
	            $lunarDay = 1;
	        } elseif ($lunarDay > $lastMonthDays) {
	            $lunarDay = $lastMonthDays;
	        }
	        
	        $leapMonth = false;
	        
	        return [$lunarMonth, $lunarDay, $leapMonth];
	    }
	    
	    // 正常情况：在春节之后
	    for ($i = 0; $i < $size; $i++) {
	        if ($daysPassed + 1 <= $acc[$i]) {
	            $monthTemp = $i + 1;
	            $lunarDay = $monthDays[$i] - ($acc[$i] - $daysPassed) + 1;
	            
	            // 确保农历日为正数
	            if ($lunarDay < 1) {
	                $lunarDay = 1;
	            }
	            
	            // 确定是否闰月
	            if ($runYue == 0) {
	                $lunarMonth = $monthTemp;
	            } else {
	                if ($monthTemp < $runYue) {
	                    $lunarMonth = $monthTemp;
	                } elseif ($monthTemp == $runYue) {
	                    $lunarMonth = $monthTemp;
	                    $leapMonth = false;
	                } elseif ($monthTemp == $runYue + 1) {
	                    $lunarMonth = $runYue;
	                    $leapMonth = true;
	                } else {
	                    $lunarMonth = $monthTemp - 1;
	                }
	            }
	            
	            break;
	        }
	    }
	    
	    return [$lunarMonth, $lunarDay, $leapMonth];
	}
    /**
     * 计算累积天数
     */
    private function accumulate(array $monthDays): array
    {
        $acc = [];
        $days = 0;
        
        foreach ($monthDays as $daysInMonth) {
            $days += $daysInMonth;
            $acc[] = $days;
        }
        
        return $acc;
    }
    

    /**
     * 获取年份名称（天干地支）
     */
    public function getYearName(int $year): string
    {
        $offset = $year - 1900 + 36;
        $gan = self::TIAN_GAN[$offset % 10];
        $zhi = self::DI_ZHI[$offset % 12];
        return $gan . $zhi . '年';
    }

	/**
	 * 获取月份名称（修复版本）
	 */
	public function getMonthName(int $month, bool $isLeap = false): string
	{
	    // 参数验证
	    if ($month < 1 || $month > 12) {
	        return ($isLeap ? '闰' : '') . $month . '月';
	    }
	    
	    $prefix = $isLeap ? '闰' : '';
	    
	    // 农历月份名称映射
	    $monthNames = [
	        1 => '正月',
	        2 => '二月',
	        3 => '三月',
	        4 => '四月',
	        5 => '五月',
	        6 => '六月',
	        7 => '七月',
	        8 => '八月',
	        9 => '九月',
	        10 => '十月',
	        11 => '冬月',
	        12 => '腊月',
	    ];
	    
	    return $prefix . $monthNames[$month];
	}

	/**
	 * 获取日期名称（修复版本）
	 */
	public function getDayName(int $day): string
	{
	    // 参数验证
	    if ($day < 1 || $day > 30) {
	        return $day . '日';
	    }
	    
	    // 农历日期的特殊名称映射
	    $dayNames = [
	        1 => '初一', 2 => '初二', 3 => '初三', 4 => '初四', 5 => '初五',
	        6 => '初六', 7 => '初七', 8 => '初八', 9 => '初九', 10 => '初十',
	        11 => '十一', 12 => '十二', 13 => '十三', 14 => '十四', 15 => '十五',
	        16 => '十六', 17 => '十七', 18 => '十八', 19 => '十九', 20 => '二十',
	        21 => '廿一', 22 => '廿二', 23 => '廿三', 24 => '廿四', 25 => '廿五',
	        26 => '廿六', 27 => '廿七', 28 => '廿八', 29 => '廿九', 30 => '三十',
	    ];
	    
	    return $dayNames[$day] ?? $day . '日';
	}

    /**
     * 获取生肖
     */
    public function getShengXiao(int $year): string
    {
        return self::SHENG_XIAO[($year - 1900) % 12] . '年';
    }

    /**
     * 获取天干地支
     */
    public function getTianDi(int $year): string
    {
        return $this->getYearName($year);
    }

    /**
     * 格式化完整字符串
     */
    public function formatFullString(int $year, int $month, int $day, bool $isLeap): string
    {
        return $this->getMonthName($month, $isLeap) . $this->getDayName($day);
    }

    /**
     * 简化方法：只返回农历月日字符串
     */
    public function getSimpleLunarDate($date = null): string
    {
        $result = $this->getLunarDate($date);
        return $result['month_name'] . $result['day_name'];
    }

    /**
     * 获取农历年的基本信息
     */
    public function getLunarYearInfo(int $year): array
    {
        if ($year < 1900 || $year > 2200) {
            throw new \InvalidArgumentException("年份必须在1900-2200范围内");
        }
        
        $yearCode = self::CHINESE_YEAR_CODE[$year - 1900];
        $runYue = $yearCode & 0xf;
        $monthDays = $this->decode($yearCode);
        
        $totalDays = array_sum($monthDays);
        $hasLeap = $runYue > 0;
        
        return [
            'year' => $year,
            'year_name' => $this->getYearName($year),
            'shengxiao' => $this->getShengXiao($year),
            'total_days' => $totalDays,
            'has_leap_month' => $hasLeap,
            'leap_month' => $hasLeap ? $runYue : null,
            'leap_days' => $hasLeap ? (($yearCode >> 16) & 1 ? 30 : 29) : null,
            'month_days' => $monthDays,
            'spring_festival' => $this->getSpringFestival($year),
        ];
    }

    /**
     * 获取春节日期
     */
    public function getSpringFestival(int $year): string
    {
        if ($year < 1900 || $year > 2200) {
            throw new \InvalidArgumentException("年份必须在1900-2200范围内");
        }
        
        $date = self::CHINESE_NEW_YEAR_DATE[$year - 1900];
        $y = (int)($date / 10000);
        $m = (int)(($date / 100) % 100);
        $d = $date % 100;
        
        return sprintf("%04d-%02d-%02d", $y, $m, $d);
    }

    /**
     * 获取农历节日
     */
    public function getLunarHoliday($date = null): ?array
    {
        $lunar = $this->getLunarDate($date);
        $key = sprintf('%02d-%02d', $lunar['month'], $lunar['day']);
        
        return $this->lunarHolidays[$key] ?? null;
    }

    /**
     * 检查是否是农历节日
     */
    public function isLunarHoliday($date = null): bool
    {
        return $this->getLunarHoliday($date) !== null;
    }

	/**
	 * 获取所有节气（公共访问方法）
	 */
	public function getSolarTerms(): array
	{
	    return $this->solarTerms;
	}
	
	/**
	 * 获取指定节气的信息
	 */
	public function getSolarTerm(string $key): ?array
	{
	    return $this->solarTerms[$key] ?? null;
	}
	
	/**
	 * 获取当前节气
	 */
	public function getCurrentSolarTerm($date = null): ?string
	{
	    $carbon = $date ? Carbon::parse($date) : Carbon::now();
	    
	    foreach ($this->solarTerms as $key => $term) {
	        if ($term['month'] == $carbon->month && $term['day'] == $carbon->day) {
	            return $key;
	        }
	    }
	    
	    return null;
	}
	
	/**
	 * 获取当前节气信息（完整版）
	 */
	public function getCurrentSolarTermInfo($date = null): ?array
	{
	    $carbon = $date ? Carbon::parse($date) : Carbon::now();
	    
	    foreach ($this->solarTerms as $key => $term) {
	        if ($term['month'] == $carbon->month && $term['day'] == $carbon->day) {
	            return [
	                'key' => $key,
	                'name' => $this->getSolarTermName($key),
	                'month' => $term['month'],
	                'day' => $term['day'],
	                'mourning' => $term['mourning'] ?? false,
	                'icon' => $this->getSolarTermIcon($key),
	            ];
	        }
	    }
	    
	    return null;
	}
	
	/**
	 * 获取节气名称
	 */
	public function getSolarTermName(string $key): string
	{
	    $names = [
	        'beginning_of_spring' => '立春',
	        'rain_water' => '雨水',
	        'awakening_of_insects' => '惊蛰',
	        'spring_equinox' => '春分',
	        'qingming' => '清明',
	        'grain_rain' => '谷雨',
	        'beginning_of_summer' => '立夏',
	        'grain_buds' => '小满',
	        'grain_in_ear' => '芒种',
	        'summer_solstice' => '夏至',
	        'minor_heat' => '小暑',
	        'major_heat' => '大暑',
	        'beginning_of_autumn' => '立秋',
	        'end_of_heat' => '处暑',
	        'white_dew' => '白露',
	        'autumn_equinox' => '秋分',
	        'cold_dew' => '寒露',
	        'frost_descent' => '霜降',
	        'beginning_of_winter' => '立冬',
	        'minor_snow' => '小雪',
	        'major_snow' => '大雪',
	        'winter_solstice' => '冬至',
	        'minor_cold' => '小寒',
	        'major_cold' => '大寒',
	    ];
	    
	    return $names[$key] ?? $key;
	}
	
	/**
	 * 获取节气图标
	 */
	public function getSolarTermIcon(string $key): string
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
	/**
	 * 获取农历节日
	 */
	public function getLunarHolidays(): array
	{
	    return $this->lunarHolidays;
	}
	
	/**
	 * 获取指定农历节日
	 */
	public function getLunarHolidayByKey(string $key): ?array
	{
	    return $this->lunarHolidays[$key] ?? null;
	}
}