{{-- resources/views/home.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      data-theme="{{ $themeData['applied_theme'] ?? 'light' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@lang('messages.app_name') - @lang('messages.under_construction')</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- 主题样式 -->
    <style>
        :root {
            --primary-color: #5aba47;
            --secondary-color: #4a90e2;
            --bg-light: #f8f9fa;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }
        
        .card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 800px;
            width: 100%;
            padding: 40px;
        }
        
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .logo i {
            font-size: 60px;
            color: var(--primary-color);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        h1 {
            font-size: 2.5rem;
            font-weight: 600;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .progress {
            height: 10px;
            border-radius: 5px;
            margin: 30px 0;
            background: #e9ecef;
        }
        
        .progress-bar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            width: 15%;
        }
        
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }
        
        .stat-item {
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 15px;
            transition: transform 0.3s;
        }
        
        .stat-item:hover {
            transform: translateY(-5px);
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
        }
        
        .stat-label {
            color: #6c757d;
            margin-top: 5px;
        }
        
        .info-box {
            background: #e8f4fd;
            border-left: 4px solid #2196f3;
            padding: 15px;
            border-radius: 10px;
            margin: 20px 0;
        }
        
        .info-box i {
            color: #2196f3;
            margin-right: 10px;
        }
        
        .btn-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            transition: transform 0.3s, box-shadow 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            color: white;
        }
        
        .btn-outline-custom {
            border: 2px solid #667eea;
            color: #667eea;
            padding: 10px 25px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
            margin-left: 10px;
        }
        
        .btn-outline-custom:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: transparent;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        .footer a {
            color: #667eea;
            text-decoration: none;
        }
        
        .footer a:hover {
            text-decoration: underline;
        }
        
        /* 多语言切换器样式 */
        .language-switcher {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 100;
        }
        
        .language-btn {
            background: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 20px;
            padding: 8px 15px;
            font-size: 0.9rem;
            cursor: pointer;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .language-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 5px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
            display: none;
        }
        
        .language-switcher:hover .language-dropdown {
            display: block;
        }
        
        .language-option {
            display: block;
            padding: 8px 20px;
            color: #333;
            text-decoration: none;
            transition: background 0.3s;
        }
        
        .language-option:hover {
            background: #f5f5f5;
        }
        
        .language-option:first-child {
            border-radius: 10px 10px 0 0;
        }
        
        .language-option:last-child {
            border-radius: 0 0 10px 10px;
        }
        
        /* 哀悼日模式 */
        body.mourning-mode {
            filter: grayscale(1);
        }
        
        /* 农历信息 */
        .lunar-info {
            position: absolute;
            top: 20px;
            left: 20px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 20px;
            padding: 8px 15px;
            font-size: 0.9rem;
            z-index: 100;
        }
        
        @media (max-width: 768px) {
            .card {
                padding: 25px;
            }
            
            h1 {
                font-size: 2rem;
            }
            
            .stats {
                grid-template-columns: 1fr 1fr;
            }
            
            .btn-custom, .btn-outline-custom {
                display: block;
                width: 100%;
                margin: 10px 0;
                text-align: center;
            }
            
            .language-switcher {
                top: 10px;
                right: 10px;
            }
            
            .lunar-info {
                top: 10px;
                left: 10px;
            }
        }
        
        @media (max-width: 480px) {
            .card {
                padding: 20px;
            }
            
            h1 {
                font-size: 1.5rem;
            }
            
            .stats {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body class="{{ $holidayData['mourning'] ?? false ? 'mourning-mode' : '' }}">
    <!-- 农历信息 -->
    @if(isset($holidayData['lunar']))
    <div class="lunar-info">
        <i class="fas fa-moon"></i>
        <span>
            {{ $holidayData['lunar']['year_name'] }} · 
            {{ $holidayData['lunar']['month_name'] }}{{ $holidayData['lunar']['day_name'] }}
        </span>
    </div>
    @endif
    
    <!-- 多语言切换器 -->
    @include('components.language-switcher')
    
    <div class="card">
        <div class="logo">
            <i class="fas fa-microphone-alt"></i>
        </div>
        
        <h1>@lang('messages.app_name')<br>@lang('messages.under_construction')</h1>
        
        <div class="progress">
            <div class="progress-bar" role="progressbar" style="width: 15%"></div>
        </div>
        
        <div class="stats">
            <div class="stat-item">
                <div class="stat-number" id="userCount">0</div>
                <div class="stat-label">@lang('messages.users')</div>
            </div>
            <div class="stat-item">
                <div class="stat-number" id="threadCount">0</div>
                <div class="stat-label">@lang('messages.threads')</div>
            </div>
            <div class="stat-item">
                <div class="stat-number" id="postCount">0</div>
                <div class="stat-label">@lang('messages.posts')</div>
            </div>
            <div class="stat-item">
                <div class="stat-number" id="attachCount">0</div>
                <div class="stat-label">@lang('messages.attachments')</div>
            </div>
        </div>
        
        <!-- 节日信息 -->
        @if(isset($holidayData['has_holiday']) && $holidayData['has_holiday'])
        <div class="info-box">
            <i class="fas {{ $holidayData['mourning'] ? 'fa-ribbon' : 'fa-gift' }}"></i>
            <strong>{{ $holidayData['holiday']['icon'] }} @lang('messages.today_is')</strong>
            @lang('messages.' . $holidayData['holiday']['key'])
            
            @if($holidayData['mourning'])
            <div class="mt-2">
                <a href="{{ route('about.mourning') }}" class="text-decoration-none">
                    <i class="fas fa-info-circle"></i> @lang('messages.learn_more')
                </a>
            </div>
            @endif
        </div>
        @endif
        
        <!-- 主题信息 -->
        <div class="info-box" style="background: #f0f0f0; border-left-color: {{ $themeData['applied_theme'] === 'dark' ? '#6f42c1' : '#007bff' }}">
            <i class="fas {{ $themeData['applied_theme'] === 'dark' ? 'fa-moon' : 'fa-sun' }}"></i>
            <strong>@lang('messages.current_theme'):</strong>
            @lang('messages.' . $themeData['current_theme']['name'])
            {{ $themeData['current_theme']['icon'] }}
            
            @if($themeData['user_preference'] === 'auto')
            <span class="badge bg-info ms-2">@lang('messages.auto_mode')</span>
            @endif
        </div>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="#" class="btn-custom">
                <i class="fas fa-sync-alt"></i> @lang('messages.view_progress')
            </a>
            <a href="https://github.com/your-repo" class="btn-outline-custom" target="_blank">
                <i class="fab fa-github"></i> @lang('messages.contribute')
            </a>
        </div>
        
        <div class="footer">
            <p>
                <i class="fas fa-code-branch"></i> @lang('messages.based_on') Laravel {{ app()->version() }} · 
                <i class="fas fa-database"></i> @lang('messages.data_from') phpwind · 
                <a href="/admin"><i class="fas fa-cog"></i> @lang('messages.admin')</a>
            </p>
            <p style="margin-top: 10px; font-size: 0.8rem;">
                ⚡ @lang('messages.preview_version')
            </p>
        </div>
    </div>
    
    <script>
        // 动态更新统计数据
        function updateStats() {
            fetch('/api/stats')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('userCount').textContent = data.users || 0;
                    document.getElementById('threadCount').textContent = data.threads || 0;
                    document.getElementById('postCount').textContent = data.posts || 0;
                    document.getElementById('attachCount').textContent = data.attachments || 0;
                })
                .catch(error => console.log('Stats not available yet'));
        }
        
        // 页面加载时更新一次
        updateStats();
        
        // 每30秒更新一次
        setInterval(updateStats, 30000);
    </script>
</body>
</html>