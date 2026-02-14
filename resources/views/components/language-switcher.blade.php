<?php
{{-- resources/views/components/language-switcher.blade.php --}}
@php
    $supportedLocales = [
		'zh' => ['name' => '中文', 'flag' => 'zh-🇨🇳'],
		'en' => ['name' => 'English', 'flag' => '🇬🇧'],
		'de' => ['name' => 'Deutsch', 'flag' => '🇩🇪'],
		'ko' => ['name' => '한국어', 'flag' => '🇰🇷'],
		'vi' => ['name' => 'Tiếng Việt', 'flag' => '🇻🇳'],
		'ru' => ['name' => 'Русский', 'flag' => '🇷🇺'],
		'hi' => ['name' => 'हिन्दी', 'flag' => '🇮🇳'],
		'ar' => ['name' => 'العربية', 'flag' => '🇸🇦'],
		'az' => ['name' => 'Azərbaycan', 'flag' => '🇦🇿'],
		'bg' => ['name' => 'Български', 'flag' => '🇧🇬'],
		'bn' => ['name' => 'বাংলা', 'flag' => '🇧🇩'],
		'bs' => ['name' => 'Bosanski', 'flag' => '🇧🇦'],
		'ca' => ['name' => 'Català', 'flag' => '🇦🇩'],
		'cs' => ['name' => 'Čeština', 'flag' => '🇨🇿'],
		'da' => ['name' => 'Dansk', 'flag' => '🇩🇰'],
		'el' => ['name' => 'Ελληνικά', 'flag' => '🇬🇷'],
		'es' => ['name' => 'Español', 'flag' => '🇪🇸'],
		'fa' => ['name' => 'فارسی', 'flag' => '🇮🇷'],
		'fi' => ['name' => 'Suomi', 'flag' => '🇫🇮'],
		'fr' => ['name' => 'Français', 'flag' => '🇫🇷'],
		'hr' => ['name' => 'Hrvatski', 'flag' => '🇭🇷'],
		'hu' => ['name' => 'Magyar', 'flag' => '🇭🇺'],
		'id' => ['name' => 'Bahasa Indonesia', 'flag' => '🇮🇩'],
		'it' => ['name' => 'Italiano', 'flag' => '🇮🇹'],
		'ja' => ['name' => '日本語', 'flag' => '🇯🇵'],
		'ka' => ['name' => 'ქართული', 'flag' => '🇬🇪'],
		'ku' => ['name' => 'Kurdî', 'flag' => '🇮🇶'],
		'nl' => ['name' => 'Nederlands', 'flag' => '🇳🇱'],
		'no' => ['name' => 'Norsk', 'flag' => '🇳🇴'],
		'pl' => ['name' => 'Polski', 'flag' => '🇵🇱'],
		'pt' => ['name' => 'Português', 'flag' => '🇵🇹'],
		'pt-br' => ['name' => 'Português (Brasil)', 'flag' => '🇧🇷'],
		'ro' => ['name' => 'Română', 'flag' => '🇷🇴'],
		'sk' => ['name' => 'Slovenčina', 'flag' => '🇸🇰'],
		'sq' => ['name' => 'Shqip', 'flag' => '🇦🇱'],
		'sr' => ['name' => 'Српски', 'flag' => '🇷🇸'],
		'sv' => ['name' => 'Svenska', 'flag' => '🇸🇪'],
		'th' => ['name' => 'ไทย', 'flag' => '🇹🇭'],
		'tr' => ['name' => 'Türkçe', 'flag' => '🇹🇷'],
		'uk' => ['name' => 'Українська', 'flag' => '🇺🇦'],
		'uk' => ['name' => 'Українська', 'flag' => '🇺🇦'],
		'ur' => ['name' => 'اردو', 'flag' => '🇵🇰'],
    ];
    $currentLocale = app()->getLocale();
@endphp

<div class="language-switcher" x-data="{ open: false }">
    <button class="language-current" @click="open = !open">
        <span class="lang-flag">{{ $supportedLocales[$currentLocale]['flag'] }}</span>
        <span class="lang-name">{{ $supportedLocales[$currentLocale]['name'] }}</span>
        <i class="fas fa-chevron-down"></i>
    </button>
    
    <div class="language-dropdown" x-show="open" @click.away="open = false" x-cloak>
        @foreach($supportedLocales as $code => $lang)
            @if($code != $currentLocale)
                <a href="{{ url('/?lang=' . $code) }}" class="language-option">
                    <span class="lang-flag">{{ $lang['flag'] }}</span>
                    <span class="lang-name">{{ $lang['name'] }}</span>
                </a>
            @endif
        @endforeach
    </div>
</div>

@push('styles')
<style>
.language-switcher {
    position: relative;
    margin-left: 15px;
}

.language-current {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 15px;
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    color: var(--text-primary);
    cursor: pointer;
    transition: all 0.3s;
}

.language-current:hover {
    background: var(--bg-secondary);
}

.language-dropdown {
    position: absolute;
    top: 100%;
    right: 0;
    margin-top: 5px;
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    box-shadow: var(--shadow);
    min-width: 160px;
    z-index: 1000;
}

.language-option {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 15px;
    color: var(--text-primary);
    text-decoration: none;
    transition: background 0.3s;
}

.language-option:hover {
    background: var(--bg-secondary);
}

.lang-flag {
    font-size: 1.2rem;
}

.lang-name {
    font-size: 0.9rem;
}

@media (max-width: 768px) {
    .language-switcher {
        margin-left: 0;
        margin-top: 10px;
    }
}
</style>
@endpush