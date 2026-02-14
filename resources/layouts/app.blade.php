{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      class="{{ $holidayData['theme_class'] ?? '' }}"
      data-theme="{{ $themeData['applied_theme'] }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@lang('messages.app_name')</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- 主题样式 -->
    <link href="{{ asset('css/themes/' . $themeData['applied_theme'] . '.css') }}" rel="stylesheet">
    
    <!-- 节日样式 -->
    @if($holidayData['has_holiday'])
        <link href="{{ asset('css/holidays/' . $holidayData['holiday']['key'] . '.css') }}" rel="stylesheet">
    @endif
    
    @stack('styles')
</head>
<body>
    <!-- 哀悼日横幅 -->
    @if($holidayData['mourning'])
        <div class="mourning-banner" id="mourningBanner">
            <div class="container">
                <span class="mourning-icon">🕯️</span>
                <span class="mourning-message">
                    @lang('messages.mourning_message')
                </span>
                <a href="{{ route('about.mourning') }}" class="mourning-link">@lang('messages.learn_more')</a>
                <button class="mourning-close" onclick="document.getElementById('mourningBanner').style.display='none'">
                    ×
                </button>
            </div>
        </div>
    @endif
    
    <!-- 农历信息 -->
    <div class="lunar-bar">
        <div class="container">
            <span class="lunar-icon">🌙</span>
            <span class="lunar-text">
                {{ $holidayData['lunar']['year_name'] }}年 
                {{ $holidayData['lunar']['month_name'] }}{{ $holidayData['lunar']['day_name'] }}
                @if($holidayData['has_holiday'])
                    · {{ $holidayData['holiday']['icon'] }} 
                    @lang('messages.' . $holidayData['holiday']['key'])
                @endif
            </span>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-microphone-alt"></i> @lang('messages.app_name')
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">@lang('messages.home')</a>
                    </li>
                    @include('components.language-switcher')
                    @include('components.theme-toggle')
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="{{ asset('js/holiday-detector.js') }}"></script>
    
    @stack('scripts')
</body>
</html>