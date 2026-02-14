<?php
// app/Services/ThemeService.php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ThemeService
{
    /**
     * 可用主题配置
     */
    protected $themes = [
        'light' => [
            'name' => 'light_mode',
            'label' => '明亮模式',
            'icon' => '☀️',
            'primary_color' => '#007bff',
            'background' => '#ffffff',
            'text_color' => '#212529',
            'css_class' => 'theme-light'
        ],
        'dark' => [
            'name' => 'dark_mode',
            'label' => '暗黑模式',
            'icon' => '🌙',
            'primary_color' => '#6f42c1',
            'background' => '#1a1a1a',
            'text_color' => '#f8f9fa',
            'css_class' => 'theme-dark'
        ],
        'auto' => [
            'name' => 'auto_mode',
            'label' => '自动模式',
            'icon' => '🔄',
            'primary_color' => '#007bff',
            'background' => 'system',
            'text_color' => 'system',
            'css_class' => 'theme-auto'
        ],
    ];

    /**
     * 主题切换规则
     */
    protected $switchRules = [
        'day_start' => 6,  // 早上6点开始白天
        'day_end' => 18,    // 晚上6点开始黑夜
    ];

    /**
     * 根据时间检测主题
     */
    public function detectThemeByTime($timezone = 'Asia/Shanghai')
    {
        try {
            $hour = Carbon::now($timezone)->hour;
            $isDayTime = $hour >= $this->switchRules['day_start'] && $hour < $this->switchRules['day_end'];
            
            return $isDayTime ? 'light' : 'dark';
        } catch (\Exception $e) {
            Log::error('时区检测失败', ['error' => $e->getMessage(), 'timezone' => $timezone]);
            return 'light'; // 默认返回明亮主题
        }
    }

    /**
     * 获取主题数据
     */
    public function getThemeData($userTheme = null, $userTimezone = null)
    {
        // 获取用户偏好（优先级：参数 > session > 默认）
        $userTheme = $userTheme ?? session('theme', 'auto');
        $userTimezone = $userTimezone ?? session('timezone', config('app.timezone', 'Asia/Shanghai'));
        
        // 验证主题有效性
        $userTheme = $this->validateTheme($userTheme);
        
        // 计算实际应用的主题
        $appliedTheme = $this->resolveAppliedTheme($userTheme, $userTimezone);
        
        // 获取主题的完整配置
        $currentThemeConfig = $this->getThemeConfig($appliedTheme);
        $userPreferenceConfig = $this->getThemeConfig($userTheme);
        
        return [
            'user_preference' => $userTheme,
            'user_preference_config' => $userPreferenceConfig,
            'applied_theme' => $appliedTheme,
            'applied_theme_config' => $currentThemeConfig,
            'themes' => $this->themes,
            'current_theme' => $currentThemeConfig,
            'timezone' => $userTimezone,
            'hour' => $this->getCurrentHour($userTimezone),
            'is_auto_mode' => $userTheme === 'auto',
            'switch_rules' => $this->switchRules,
            'cache_key' => $this->generateCacheKey(auth()->id(), $userTimezone),
        ];
    }

    /**
     * 解析实际应用的主题
     */
    protected function resolveAppliedTheme($userTheme, $timezone)
    {
        if ($userTheme === 'auto') {
            return $this->detectThemeByTime($timezone);
        }
        
        return $userTheme;
    }

    /**
     * 验证主题是否有效
     */
    protected function validateTheme($theme)
    {
        return isset($this->themes[$theme]) ? $theme : 'auto';
    }

    /**
     * 获取主题配置
     */
    public function getThemeConfig($theme)
    {
        return $this->themes[$theme] ?? $this->themes['light'];
    }

    /**
     * 获取当前小时
     */
    protected function getCurrentHour($timezone)
    {
        try {
            return Carbon::now($timezone)->hour;
        } catch (\Exception $e) {
            return Carbon::now()->hour;
        }
    }

    /**
     * 生成缓存键
     */
    protected function generateCacheKey($userId, $timezone)
    {
        return "theme_data_{$userId}_{$timezone}_" . date('Y-m-d-H');
    }

    /**
     * 更新用户主题偏好（完整版）
     */
    public function updateUserTheme($userId, $theme, $timezone = null)
    {
        $result = [
            'success' => false,
            'session_updated' => false,
            'database_updated' => false,
            'cache_cleared' => false,
            'theme' => $theme,
            'applied_theme' => null,
        ];

        try {
            // 验证主题
            $theme = $this->validateTheme($theme);
            
            // 1. 更新 Session（总是执行）
            session(['theme' => $theme]);
            if ($timezone) {
                session(['timezone' => $timezone]);
            }
            $result['session_updated'] = true;

            // 2. 如果用户已登录，更新数据库
            if ($userId) {
                $user = User::find($userId);
                if ($user) {
                    $updateData = ['theme_preference' => $theme];
                    if ($timezone) {
                        $updateData['timezone'] = $timezone;
                    }
                    
                    $user->update($updateData);
                    $result['database_updated'] = true;
                    
                    // 3. 清除用户相关的主题缓存
                    $this->clearUserThemeCache($userId);
                    $result['cache_cleared'] = true;
                }
            }

            // 4. 获取实际应用的主题（用于返回）
            $appliedTheme = $this->resolveAppliedTheme($theme, $timezone ?? session('timezone'));
            $result['applied_theme'] = $appliedTheme;
            $result['success'] = true;

            // 5. 记录日志
            Log::info('用户主题更新', [
                'user_id' => $userId,
                'theme' => $theme,
                'timezone' => $timezone,
                'applied_theme' => $appliedTheme
            ]);

        } catch (\Exception $e) {
            Log::error('更新用户主题失败', [
                'user_id' => $userId,
                'theme' => $theme,
                'error' => $e->getMessage()
            ]);
            
            $result['error'] = $e->getMessage();
        }

        return $result;
    }

    /**
     * 批量更新用户主题
     */
    public function bulkUpdateUserTheme(array $userIds, $theme)
    {
        $results = [];
        foreach ($userIds as $userId) {
            $results[$userId] = $this->updateUserTheme($userId, $theme);
        }
        return $results;
    }

    /**
     * 清除用户主题缓存
     */
    protected function clearUserThemeCache($userId)
    {
        // 清除该用户的所有主题相关缓存
        $cachePattern = "theme_data_{$userId}_*";
        // 这里可以根据你的缓存驱动实现具体的清除逻辑
        return true;
    }

    /**
     * 获取用户主题历史
     */
    public function getUserThemeHistory($userId, $days = 30)
    {
        // 这里可以从数据库查询用户的主题变更历史
        // 需要先创建主题历史记录表
        return [];
    }

    /**
     * 获取所有可用主题
     */
    public function getAvailableThemes()
    {
        return array_keys($this->themes);
    }

    /**
     * 获取主题统计信息
     */
    public function getThemeStatistics()
    {
        $stats = [
            'total_users' => User::count(),
            'theme_distribution' => [],
            'auto_mode_users' => 0,
        ];

        foreach ($this->themes as $key => $theme) {
            $count = User::where('theme_preference', $key)->count();
            $stats['theme_distribution'][$key] = [
                'count' => $count,
                'percentage' => $stats['total_users'] > 0 
                    ? round(($count / $stats['total_users']) * 100, 2) 
                    : 0,
                'label' => $theme['label']
            ];
        }

        $stats['auto_mode_users'] = $stats['theme_distribution']['auto']['count'] ?? 0;

        return $stats;
    }

    /**
     * 检查当前是否应该切换主题（用于定时任务）
     */
    public function shouldSwitchTheme($timezone = 'Asia/Shanghai')
    {
        $hour = $this->getCurrentHour($timezone);
        $currentTheme = $this->detectThemeByTime($timezone);
        
        return [
            'should_switch' => true, // 总是返回true，因为auto模式会实时计算
            'current_hour' => $hour,
            'recommended_theme' => $currentTheme,
            'switch_point' => $hour == $this->switchRules['day_start'] || 
                              $hour == $this->switchRules['day_end']
        ];
    }

    /**
     * 重置用户主题为默认
     */
    public function resetUserTheme($userId)
    {
        return $this->updateUserTheme($userId, 'auto');
    }
}