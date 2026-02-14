# 🚀 项目简介

**phpwind Laravel** 是一个使用 Laravel 框架重构的 phpwind 论坛项目。保留了 phpwind 的核心数据结构和业务逻辑，但采用了现代 Laravel 架构，提供了更好的性能、安全性和可维护性。

### ✨ 核心特性

<div class="grid grid-cols-3 gap-4 mt-4">
  <div class="feature-item">
    <span class="feature-icon">✅</span>
    <span>完整的用户系统</span>
  </div>
  <div class="feature-item">
    <span class="feature-icon">✅</span>
    <span>多语言支持（中/英/日/韩等）</span>
  </div>
  <div class="feature-item">
    <span class="feature-icon">✅</span>
    <span>农历节日自动识别</span>
  </div>
  <div class="feature-item">
    <span class="feature-icon">✅</span>
    <span>明暗主题切换</span>
  </div>
  <div class="feature-item">
    <span class="feature-icon">✅</span>
    <span>哀悼日灰度模式</span>
  </div>
  <div class="feature-item">
    <span class="feature-icon">✅</span>
    <span>响应式设计</span>
  </div>
  <div class="feature-item">
    <span class="feature-icon">✅</span>
    <span>基于 Laravel 12</span>
  </div>
  <div class="feature-item">
    <span class="feature-icon">✅</span>
    <span>一键复制代码</span>
  </div>
  <div class="feature-item">
    <span class="feature-icon">✅</span>
    <span>全文搜索</span>
  </div>
</div>

### 🎯 项目目标

- 将过时的 phpwind 代码迁移到现代 Laravel 框架
- 保留原有数据结构和业务逻辑
- 提供更好的用户体验和国际化支持
- 易于维护和扩展

---

## 📚 快速导航

<div class="grid grid-cols-2 gap-4 mt-8">
  <div class="nav-card">
    <h3 class="text-lg font-bold">👥 用户指南</h3>
    <ul class="mt-2 space-y-1">
      <li><a href="/guide/getting-started">快速开始</a></li>
      <li><a href="/guide/lunar-calendar">农历功能</a></li>
      <li><a href="/guide/theme-switch">主题切换</a></li>
      <li><a href="/guide/mourning-days">哀悼日模式</a></li>
      <li><a href="/guide/multi-language">多语言支持</a></li>
      <li><a href="/guide/faq">常见问题</a></li>
    </ul>
  </div>
  <div class="nav-card">
    <h3 class="text-lg font-bold">💻 开发文档</h3>
    <ul class="mt-2 space-y-1">
      <li><a href="/dev/architecture">架构设计</a></li>
      <li><a href="/dev/database">数据库设计</a></li>
      <li><a href="/dev/api">API 文档</a></li>
      <li><a href="/dev/#环境要求">环境要求</a></li>
      <li><a href="/dev/#安装步骤">安装指南</a></li>
      <li><a href="/contribute">贡献指南</a></li>
    </ul>
  </div>
</div>

---

## 📦 快速开始

```bash
# 克隆项目
git clone https://github.com/hestiacn/phpwind-laravel.git
cd phpwind-laravel

# 安装依赖
composer install
pnpm install

# 启动文档服务
pnpm run docs:dev