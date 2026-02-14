@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <p class="mb-3">
                        @lang('messages.welcome_message')
                    </p>
                    
                    {{-- 主题信息显示 --}}
                    @isset($themeData)
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            @lang('messages.current_theme'): 
                            @lang('messages.' . $themeData['current_theme']['name'])
                            ({{ $themeData['current_theme']['icon'] }})
                            <br>
                            <small class="text-muted">
                                @lang('messages.applied'): {{ $themeData['applied_theme'] }}
                                @if($themeData['user_preference'] === 'auto')
                                    ({{ $themeData['hour'] }}:00 - @lang('messages.auto'))
                                @endif
                            </small>
                        </div>

                        {{-- 主题切换组件 --}}
                        <x-theme-toggle :themeData="$themeData" />
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            Theme service not available
                        </div>
                    @endisset
                </div>
            </div>
        </div>
    </div>
</div>
@endsection