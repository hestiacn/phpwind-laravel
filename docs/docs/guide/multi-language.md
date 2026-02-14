# 多语言支持

## 🌐 支持的语言

论坛目前已支持以下语言：

| 语言 | 代码 | 标识 |
|------|------|------|
| 简体中文 | zh-CN | 🇨🇳 |
| 英文 | en | 🇬🇧 |
| 日文 | ja | 🇯🇵 |
| 韩文 | ko | 🇰🇷 |
| 德文 | de | 🇩🇪 |
| 法文 | fr | 🇫🇷 |
| 俄文 | ru | 🇷🇺 |
| 越南文 | vi | 🇻🇳 |
| 印地文 | hi | 🇮🇳 |

## 🔄 如何切换语言

### 方法一：语言切换器

页面右上角有一个语言切换按钮：

1. 点击当前语言标识（如 "🇨🇳 中文"）
2. 从下拉菜单中选择您想要的语言
3. 页面立即刷新，显示对应语言的内容

### 方法二：自动检测

首次访问时，论坛会自动检测您浏览器的语言设置：

- 如果您的浏览器设置为中文，自动显示中文界面
- 如果您的浏览器设置为英文，自动显示英文界面
- 如果您的浏览器设置为其他支持的语言，自动显示对应语言
- 如果不支持您的浏览器语言，默认显示中文

### 方法三：URL 参数

您也可以通过在 URL 中添加 `?lang=xx` 参数来强制切换语言：

```
https://bbs.hestiamb.org/?lang=en  # 英文
https://bbs.hestiamb.org/?lang=ja  # 日文
https://bbs.hestiamb.org/?lang=ko  # 韩文
```

## 📝 语言包内容

每个语言包包含以下内容的翻译：

- 导航菜单（首页、版块、个人中心等）
- 按钮文字（登录、注册、保存等）
- 提示信息（成功、错误、警告等）
- 节日名称（春节、中秋节等）
- 农历相关术语（正月、腊月、初一、十五等）

## 🎯 国际化体验优化

### 农历显示

- **中文用户**：直接显示完整的农历信息
  ```
  乙巳年 · 正月初二
  ```

- **非中文用户**：显示提示信息
  ```
  🌙 Lunar Calendar (Chinese Traditional)
  ```
  鼠标悬停时显示具体农历日期

### 哀悼日显示

- **中文用户**：显示中文哀悼信息
- **非中文用户**：显示英文哀悼信息（如果有翻译）

## 🔧 技术实现

### 前端语言检测

```javascript
// 检测浏览器语言
const userLang = navigator.language || navigator.userLanguage;
const supportedLangs = ['zh', 'en', 'ja', 'ko'];

if (supportedLangs.includes(userLang.substring(0, 2))) {
  // 切换到对应语言
  window.location.href = `/?lang=${userLang.substring(0, 2)}`;
}
```

### 后端语言文件

```php
// resources/lang/zh/messages.php
return [
    'app_name' => '配音艺术论坛',
    'home' => '首页',
    'current_theme' => '当前主题',
    // ...
];

// resources/lang/en/messages.php
return [
    'app_name' => 'Dubbing Art Forum',
    'home' => 'Home',
    'current_theme' => 'Current Theme',
    // ...
];
```

## ❓ 常见问题

**Q: 我想帮助翻译新的语言，如何贡献？**  
A: 欢迎贡献！请访问我们的 [GitHub 仓库](https://github.com/hestiacn/phpwind-laravel)，提交新的语言文件或改进现有翻译。

**Q: 为什么某些内容没有翻译？**  
A: 翻译工作正在持续进行中。如果您发现缺失的翻译，请在 GitHub 上提交 Issue 或 Pull Request。

**Q: 语言设置会保存在哪里？**  
A: 登录用户的语言偏好会保存在数据库中；未登录用户的语言偏好会保存在浏览器的 Cookie 中。
```