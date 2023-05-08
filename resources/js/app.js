import './bootstrap';
import { createApp } from 'vue';
import IndexComponent from './components/IndexComponent.vue';


const app = createApp(IndexComponent);

app.mount('#app');
