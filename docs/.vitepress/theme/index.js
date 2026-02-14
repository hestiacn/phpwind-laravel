import { h } from 'vue';
import DefaultTheme from 'vitepress/theme';
import BackToTop from './components/BackToTop.vue';
import CopyToClipboardInput from './components/CopyToClipboardInput.vue';
import NotFound from './components/NotFound.vue';
import ElementPlus from 'element-plus';
import './styles/custom.scss';
import './styles/rainbow.css';
import 'element-plus/dist/index.css';

export default {
  extends: DefaultTheme,
  
  Layout() {
    return h(DefaultTheme.Layout, null, {
      'not-found': () => h(NotFound),
      'doc-after': () => h(BackToTop),
    });
  },
  
  enhanceApp(ctx) {
    // 使用 Element Plus
    ctx.app.use(ElementPlus);
    
    // 注册全局组件
    ctx.app.component('BackToTop', BackToTop);
    ctx.app.component('NotFound', NotFound);
    ctx.app.component('CopyToClipboardInput', CopyToClipboardInput);
  }
};