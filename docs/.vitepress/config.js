import navConfig from "./nav";
const BUILD_YEAR = new Date().getFullYear();
const PROJECT_NAME = "phpwind-Laravel";
const PROJECT_DESCRIPTION = "phpwind项目使用Laravel重构";
const PROJECT_REPO = "https://github.com/hestiacn/phpwind-Laravel";

export default {
  lang: 'zh-CN',
  markdown: {
    codeCopyButtonTitle: '复制代码'
  },
  title: `${PROJECT_NAME} | 文档`,
  description: PROJECT_DESCRIPTION,
  cleanUrls: false,
  
  head: [
    ['script', {}, `(function(){function updateYear(){var el=document.getElementById('dynamicYear');if(el){var year=new Date().getFullYear();el.textContent!==year.toString()&&(el.textContent=year);}}document.readyState==='loading'?document.addEventListener('DOMContentLoaded',updateYear):updateYear();setInterval(updateYear,3600000);})();`],
    ['meta', { name: 'viewport', content: 'width=device-width, initial-scale=1.0, viewport-fit=cover' }],
    ['meta', { name: 'theme-color', content: '#2563eb' }],
    ['meta', { name: 'keywords', content: `${PROJECT_NAME}, 文档, 使用指南, 开发文档` }],
    
    ['link', { rel: 'icon', href: '/favicon.pub/favicon.ico', type: 'image/x-icon'}],
    ['link', { rel: 'icon', href: '/favicon.pub/favicon-16x16.png', type: 'image/png', sizes: '16x16'}],
    ['link', { rel: 'icon', href: '/favicon.pub/favicon-32x32.png', type: 'image/png', sizes: '32x32'}],
    ['link', { rel: 'icon', href: '/favicon.pub/favicon-48x48.png', type: 'image/png', sizes: '48x48'}],
    ['link', { rel: 'apple-touch-icon', href: '/favicon.pub/apple-touch-icon.png', sizes: '180x180'}],
    ['link', { rel: 'icon', href: '/favicon.pub/android-chrome-192x192.png', sizes: '192x192', type: 'image/png'}],
    ['link', { rel: 'icon', href: '/favicon.pub/favicon.svg', type: 'image/svg+xml'}],
    ['link', { rel: 'mask-icon', href: '/favicon.pub/safari-pinned-tab.svg', color: '#ffd100'}],
    ['link', { rel: 'manifest', href: '/favicon.pub/site.webmanifest'}],
  ],
  
  themeConfig: {
    logo: "/logo.svg",
    siteTitle: PROJECT_NAME,
	  nav: navConfig.nav,
    socialLinks: [
      { icon: 'github', link: PROJECT_REPO },
      { icon: 'twitter', link: 'https://twitter.com/你的账号' },
      { icon: 'discord', link: 'https://discord.gg/你的邀请码' },
    ],
    
    search: {
      provider: "local",
      options: {
        placeholder: "搜索文档",
        minMatchCharLength: 1,
        threshold: 0.3,
        distance: 10000,
        keys: ["title", "content", "headers"],
        tokenize: (text) => {
          return text.split('').filter(char => char.trim());
        },
        translations: {
          button: { buttonText: "搜索文档" },
          modal: {
            noResultsText: "未找到相关内容",
            displayDetails: "显示详细信息",
            resetButtonTitle: "清除搜索条件",
            errorScreen: {
              titleText: "无法获取结果",
              helpText: "请检查网络连接",
            },
            footer: {
              selectText: "选择",
              navigateText: "切换",
              closeText: "关闭",
            },
          },
        },
      },
    },
    
    sidebar: {
      "/guide/": [
        {
          text: "入门指南",
          collapsible: true,
          items: [
            { text: "介绍", link: "/guide/" },
            { text: "快速开始", link: "/guide/getting-started" },
            { text: "注册登录", link: "/guide/getting-started#注册登录" },
            { text: "个人设置", link: "/guide/getting-started#个人设置" }
          ]
        },
        {
          text: "特色功能",
          collapsible: true,
          items: [
            { text: "农历节日", link: "/guide/lunar-calendar" },
            { text: "主题切换", link: "/guide/theme-switch" },
            { text: "哀悼日模式", link: "/guide/mourning-days" },
            { text: "多语言支持", link: "/guide/multi-language" }
          ]
        },
        {
          text: "帮助中心",
          collapsible: true,
          items: [
            { text: "常见问题", link: "/guide/faq" },
            { text: "联系我们", link: "/guide/faq#联系我们" }
          ]
        }
      ],
      
      // 侧边栏 - 开发文档
      "/dev/": [
        {
          text: "项目概览",
          items: [
            { text: "介绍", link: "/dev/" },
            { text: "架构设计", link: "/dev/architecture" }
          ]
        },
        {
          text: "技术细节",
          items: [
            { text: "数据库设计", link: "/dev/database" },
            { text: "API 文档", link: "/dev/api" },
            { text: "农历计算", link: "/dev/architecture#农历服务" },
            { text: "主题系统", link: "/dev/architecture#主题服务" }
          ]
        },
        {
          text: "部署运维",
          items: [
            { text: "环境要求", link: "/dev/architecture#环境要求" },
            { text: "安装指南", link: "/dev/architecture#安装步骤" }
          ]
        }
      ]
    },
    
    editLink: {
      pattern: `${PROJECT_REPO}/edit/master/docs/:path`,
      text: '在 GitHub 上编辑此页面',
    },
    
    footer: {
      message: '基于 <a href="https://vitepress.dev" target="_blank">VitePress</a> 构建',
      copyright: `版权所有 © 2025-<span id="dynamicYear">${BUILD_YEAR}</span> ${PROJECT_NAME} | <a href="${PROJECT_REPO}" target="_blank">开源项目</a>`
    },
    
    outlineTitle: "目录导航",
    lightModeSwitchTitle: '浅色模式',
    darkModeSwitchTitle: '深色模式',
    
    lastUpdated: {
      text: '最后更新',
      formatOptions: {
        dateStyle: 'short',
        timeStyle: 'medium',
        timeZone: 'Asia/Shanghai',
      },
    },
    
    docFooter: {
      prev: "上一篇",
      next: "下一篇",
    },
    
    sidebarMenuLabel: '菜单',
    darkModeSwitchLabel: '主题',
    returnToTopLabel: '回到顶部',
  },
};