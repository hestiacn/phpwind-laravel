{{-- resources/views/components/theme-toggle.blade.php --}}
<div class="theme-toggle-wrapper" x-data="themeSwitcher()">
    <button class="theme-toggle-btn" @click="toggleDropdown">
        <span class="theme-icon">{{ $themeData['current_theme']['icon'] ?? '🎨' }}</span>
        <span class="theme-name">
            @isset($themeData['current_theme']['name'])
                @lang('messages.' . $themeData['current_theme']['name'])
            @else
                @lang('messages.default_theme')
            @endisset
        </span>
        <i class="fas fa-chevron-down"></i>
    </button>
    
    <div class="theme-dropdown" 
         x-show="open" 
         @click.away="open = false" 
         x-transition
         x-cloak>
        @forelse($themeData['themes'] ?? [] as $key => $theme)
            <button class="theme-option" @click="setTheme('{{ $key }}')">
                <span class="theme-icon">{{ $theme['icon'] ?? '○' }}</span>
                <span class="theme-name">@lang('messages.' . ($theme['name'] ?? 'unknown'))</span>
                @if($key === 'auto' && isset($themeData['hour']))
                    <span class="theme-tip">({{ $themeData['hour }}:00)</span>
                @endif
            </button>
        @empty
            <div class="theme-option disabled">@lang('messages.no_themes_available')</div>
        @endforelse
    </div>
</div>

@push('scripts')
<script>
function themeSwitcher() {
    return {
        open: false,
        toggleDropdown() {
            this.open = !this.open;
        },
        async setTheme(theme) {
            try {
                const response = await fetch('{{ route('api.theme') ?? '/api/theme' }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ theme: theme })
                });
                
                if (response.ok) {
                    window.location.reload();
                } else {
                    console.error('Theme switch failed');
                }
            } catch (error) {
                console.error('Error:', error);
            }
            this.open = false;
        }
    }
}
</script>
@endpush