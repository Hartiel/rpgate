// Define o tipo genérico para qualquer arquivo que termine em .vue
declare module '*.vue' {
    import type { DefineComponent } from 'vue';
    const component: DefineComponent<{}, {}, any>;
}
