{{-- resources/views/home.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      data-theme="{{ $themeData['applied_theme'] ?? 'light' }}"
      class="{{ isset($holidayData['mourning']) && $holidayData['mourning'] ? 'mourning-mode' : '' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('messages.app_name') }} - {{ __('messages.under_construction') }}</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
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
        
        /* 语言切换器样式 */
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
        
        /* 主题切换器样式 */
        .theme-toggle {
            position: absolute;
            top: 20px;
            right: 120px;
            z-index: 100;
        }
        
        .theme-btn {
            background: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 20px;
            padding: 8px 15px;
            font-size: 0.9rem;
            cursor: pointer;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        /* 农历信息样式 */
        .lunar-info {
            position: absolute;
            top: 20px;
            left: 20px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 20px;
            padding: 8px 15px;
            font-size: 0.9rem;
            z-index: 100;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        /* 哀悼日模式 */
        body.mourning-mode {
            filter: grayscale(1);
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
            
            .language-switcher,
            .theme-toggle,
            .lunar-info {
                position: static;
                margin: 10px auto;
                width: fit-content;
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
    @stack('styles')
</head>
<body>
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
    
    <!-- 主题切换器 -->
    <div class="theme-toggle">
        <div class="dropdown">
            <button class="theme-btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
                {{ $themeData['current_theme']['icon'] }} 
                {{ __('messages.' . $themeData['current_theme']['name']) }}
            </button>
            <ul class="dropdown-menu">
                @foreach($themeData['themes'] as $key => $theme)
                <li>
                    <a class="dropdown-item" href="#" onclick="switchTheme('{{ $key }}')">
                        {{ $theme['icon'] }} {{ __('messages.' . $theme['name']) }}
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    
    <!-- 多语言切换器 -->
    <div class="language-switcher">
        <button class="language-btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
            {{ strtoupper(app()->getLocale()) }}
        </button>
        <ul class="dropdown-menu">
            @foreach(['zh' => '中文', 'en' => 'English', 'ja' => '日本語', 'ko' => '한국어'] as $code => $name)
            <li>
                <a class="dropdown-item" href="{{ url('/?lang=' . $code) }}">
                    {{ $name }}
                </a>
            </li>
            @endforeach
        </ul>
    </div>
    
    <div class="card">
        <div class="logo">
            <i class="fas fa-microphone-alt"></i>
        </div>
        
        <h1>{{ __('messages.app_name') }}<br>{{ __('messages.under_construction') }}</h1>
        
        <div class="progress">
            <div class="progress-bar" role="progressbar" style="width: 15%"></div>
        </div>
        
        <div class="stats">
            <div class="stat-item">
                <div class="stat-number" id="userCount">0</div>
                <div class="stat-label">{{ __('messages.users') }}</div>
            </div>
            <div class="stat-item">
                <div class="stat-number" id="threadCount">0</div>
                <div class="stat-label">{{ __('messages.threads') }}</div>
            </div>
            <div class="stat-item">
                <div class="stat-number" id="postCount">0</div>
                <div class="stat-label">{{ __('messages.posts') }}</div>
            </div>
            <div class="stat-item">
                <div class="stat-number" id="attachCount">0</div>
                <div class="stat-label">{{ __('messages.attachments') }}</div>
            </div>
        </div>
        
        <!-- 节日信息 -->
        @if(isset($holidayData['has_holiday']) && $holidayData['has_holiday'])
        <div class="info-box">
            <i class="fas {{ isset($holidayData['mourning']) && $holidayData['mourning'] ? 'fa-ribbon' : 'fa-gift' }}"></i>
            <strong>{{ $holidayData['holiday']['icon'] ?? '🎉' }} {{ __('messages.today_is') }}</strong>
            {{ __('messages.' . ($holidayData['holiday']['key'] ?? '')) }}
            
            @if(isset($holidayData['mourning']) && $holidayData['mourning'])
            <div class="mt-2">
                <a href="{{ route('about.mourning') }}" class="text-decoration-none">
                    <i class="fas fa-info-circle"></i> {{ __('messages.learn_more') }}
                </a>
            </div>
            @endif
        </div>
        @endif
        
        <!-- 主题信息 -->
        <div class="info-box" style="background: #f0f0f0; border-left-color: {{ isset($themeData['applied_theme']) && $themeData['applied_theme'] === 'dark' ? '#6f42c1' : '#007bff' }}">
            <i class="fas {{ isset($themeData['applied_theme']) && $themeData['applied_theme'] === 'dark' ? 'fa-moon' : 'fa-sun' }}"></i>
            <strong>{{ __('messages.current_theme') }}:</strong>
            {{ __('messages.' . ($themeData['current_theme']['name'] ?? 'light_mode')) }}
            {{ $themeData['current_theme']['icon'] ?? '☀️' }}
            
            @if(isset($themeData['user_preference']) && $themeData['user_preference'] === 'auto')
            <span class="badge bg-info ms-2">{{ __('messages.auto_mode') }}</span>
            @endif
        </div>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="#" class="btn-custom">
                <i class="fas fa-sync-alt"></i> {{ __('messages.view_progress') }}
            </a>
            <a href="https://github.com/your-repo" class="btn-outline-custom" target="_blank">
                <i class="fab fa-github"></i> {{ __('messages.contribute') }}
            </a>
        </div>
        
        <div class="footer">
            <p>
                <i class="fas fa-code-branch"></i> {{ __('messages.based_on') }} Laravel {{ app()->version() }} · 
                <i class="fas fa-database"></i> {{ __('messages.data_from') }} phpwind · 
                <a href="/admin"><i class="fas fa-cog"></i> {{ __('messages.admin') }}</a>
            </p>
            <p style="margin-top: 10px; font-size: 0.8rem;">
                ⚡ {{ __('messages.preview_version') }}
            </p>
        </div>
    </div>
    
    <script>
        // 主题切换函数
        function switchTheme(theme) {
            fetch('{{ route("api.theme") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ theme: theme })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                }
            });
        }
        
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
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>