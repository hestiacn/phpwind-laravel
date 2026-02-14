<?php
// app/Http/Middleware/Localization.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class Localization
{
    // 支持的语言列表
    protected $supportedLocales = ['ar', 'az', 'bg', 'bn', 'bs', 'ca', 'cs', 'da', 'de', 'el', 'en', 'es', 'fa', 'fi', 'fr', 'hr', 'hu', 'id', 'it', 'ja', 'ka', 'ku', 'ko', 'nl', 'no', 'pl', 'pt', 'pt-br', 'ro', 'ru', 'sk', 'sq', 'sr', 'sv', 'th', 'tr', 'uk', 'ur', 'vi', 'zh-cn', 'zh-tw'];
    
    public function handle($request, Closure $next)
    {
        // 1. 检查 URL 参数（优先级最高）
        if ($request->has('lang') && in_array($request->lang, $this->supportedLocales)) {
            Session::put('locale', $request->lang);
            App::setLocale($request->lang);
            return $next($request);
        }
        
        // 2. 检查 Session
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
            return $next($request);
        }
        
        // 3. 检查浏览器语言
        $browserLang = substr($request->server('HTTP_ACCEPT_LANGUAGE'), 0, 2);
        if (in_array($browserLang, $this->supportedLocales)) {
            Session::put('locale', $browserLang);
            App::setLocale($browserLang);
            return $next($request);
        }
        
        // 4. 默认中文
        App::setLocale('zh-cn');
        Session::put('locale', 'zh-cn');
        
        return $next($request);
    }
}