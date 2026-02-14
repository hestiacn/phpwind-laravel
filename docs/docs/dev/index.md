# 开发文档

## 🏗️ 项目概览

**phpwind Laravel** 是一个使用 Laravel 框架重构的 phpwind 论坛项目。

### 技术栈

| 类别 | 技术 |
|------|------|
| 后端框架 | Laravel 12 |
| 前端 | Blade + Bootstrap 5 + Font Awesome |
| 数据库 | MySQL/MariaDB |
| 缓存 | Redis (可选) |
| 队列 | Redis/Database |
| 任务调度 | Laravel Scheduler |
| 多语言 | Laravel Localization |
| 农历计算 | 自定义 LunarService |

### 目录结构

```
public_html/
├── app/
│   ├── Http/
│   │   ├── Controllers/     # 控制器
│   │   └── Middleware/       # 中间件
│   ├── Models/               # 模型
│   └── Services/             # 服务类
│       ├── LunarService.php  # 农历服务
│       ├── HolidayService.php # 节日服务
│       └── ThemeService.php  # 主题服务
├── resources/
│   └── views/                # Blade 视图
│       ├── home.blade.php    # 首页
│       └── about/
│           └── mourning.blade.php # 哀悼日页面
├── routes/
│   └── web.php                # 路由
└── database/
    └── migrations/            # 数据库迁移
```

## 🔧 环境要求

- PHP >= 8.3
- MySQL >= 5.7 / MariaDB >= 10.3
- Composer 2.x
- Node.js >= 22 (用于 VitePress 文档)
- pnpm >= 10

## 📦 安装步骤

```bash
# 1. 克隆项目
git clone https://github.com/hestiacn/phpwind-laravel.git
cd phpwind-laravel

# 2. 安装 PHP 依赖
composer install

# 3. 配置环境
cp .env.example .env
php artisan key:generate

# 4. 配置数据库
# 编辑 .env 文件，设置数据库连接信息

# 5. 运行数据库迁移
php artisan migrate

# 6. 安装文档依赖（可选）
pnpm install
pnpm run docs:dev
```

## 🚀 开发指南

### 添加新的语言

1. 在 `resources/lang/` 下创建新的语言目录
2. 复制 `zh/messages.php` 并翻译
3. 在 `Localization.php` 中间件中添加语言代码

### 添加新的节日

编辑 `HolidayService.php` 中的相应数组：

```php
protected $solarHolidays = [
    '01-01' => 'new_year',
    // 添加新节日...
];

protected $lunarHolidays = [
    '01-01' => ['key' => 'spring_festival', 'days' => 15],
    // 添加新农历节日...
];
```

### 自定义主题

编辑 `home.blade.php` 中的 CSS 变量：

```css
:root {
    --primary-color: #5aba47;  /* 修改主题色 */
    --secondary-color: #4a90e2;
}
```
```

---

## 🚀 **10. 使用说明**

### 安装文档

```bash
# 1. 进入项目根目录
cd /home/web/web/you-domain/public_html

# 2. 确保 package.json 已更新
# 使用之前确认的配置

# 3. 安装依赖
pnpm install

# 4. 启动文档开发服务器
pnpm run docs:dev

# 5. 构建文档
pnpm run docs:build
```

### 访问文档

- 开发模式：`http://localhost:5173`
- 构建后：`docs/.vitepress/dist/` 目录下的静态文件

### 部署到子域名

```nginx
server {
    listen 80;
    server_name you-domain;
    root /home/web/web/you-domain/public_html/docs/.vitepress/dist;
    index index.html;
    
    location / {
        try_files $uri $uri/ /index.html;
    }
}
```