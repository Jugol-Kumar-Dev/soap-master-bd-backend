import './bootstrap';
import '../css/app.css';
import "@/../sass/app.scss"
import { createApp, h } from 'vue';
import { createInertiaApp, Link, Head} from '@inertiajs/vue3';


import 'summernote/dist/summernote-lite.css'
import 'summernote/dist/summernote-lite.js'


import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';

import vSelect from 'vue-select'
import { createPinia } from "pinia";
import CKEditor from '@ckeditor/ckeditor5-vue'
import store from './Store'
import { createVbPlugin } from 'vue3-plugin-bootstrap5'
import { Alert, Button, Carousel, Collapse, Dropdown, Modal, Offcanvas, Popover, ScrollSpy, Tab, Toast, Tooltip } from 'bootstrap'

let vbPlugin = createVbPlugin({ Alert, Button, Carousel, Collapse, Dropdown, Modal, Offcanvas, Popover, ScrollSpy, Tab, Toast, Tooltip  })

import 'vue-select/dist/vue-select.css';
import 'vue3-datepicker/dist/vue3-datepicker.css'

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';
const appUrl = import.meta.env.FRONTEND_URL || 'https://amcpaedia.com'
window.$appurl = appUrl

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({el, App, props, plugin}) {
        return createApp({render: () => h(App, props)})
            .use(plugin)
            .use(ZiggyVue)
            .use(vbPlugin)
            .use(store)
            .use(CKEditor)
            .use(appUrl)
            .use(createPinia())
            .component("Link", Link)
            .component("Head", Head)
            .component('v-select', vSelect)
            .mount(el)

    },
    progress: {
        color: '#4B5563',
    },
}).then().catch();
