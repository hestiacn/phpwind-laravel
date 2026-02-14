// public/js/holiday-detector.js
(function() {
    'use strict';
    
    // 如果页面有哀悼日，自动添加灰度滤镜
    if (document.body.classList.contains('holiday-mourning')) {
        document.body.style.filter = 'grayscale(1)';
        document.body.style.webkitFilter = 'grayscale(1)';
    }
    
    // 发送时区信息到服务器
    if (navigator.cookieEnabled) {
        const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
        document.cookie = `user_timezone=${timezone}; path=/; max-age=86400`;
    }
})();