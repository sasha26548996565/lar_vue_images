<template>
    <div class="container">
        <input type="text" v-model="title" required class="form-control mt-2" placeholder="Enter title...">
        <div ref="dropzone" class="btn d-block bg-dark mt-2 text-center text-light">
            Upload images
        </div>
        <input @click.prevent="store()" type="submit" class="btn btn-success mt-2" value="Create post">
        <div class="mt-5" v-if="post">
            <h5>{{ post.title }}</h5>
            <div v-for="image in post.images" :key="image.id" class="mt-2">
                <img :src="image.url" class="w-25" :alt="post.title">
            </div>
            <hr>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import Dropzone from 'dropzone';

export default {
    name: 'IndexComponent',
    data() {
        return {
            post: null,
            title: '',
            dropzone: null,
        }
    },
    mounted() {
        this.dropzone = new Dropzone(this.$refs.dropzone, {
            url: '/api/post/store',
            autoProcessQueue: false,
            addRemoveLinks: true
        });
        this.getPost();
    },
    methods: {
        getPost() {
            axios.get('/api/post')
                .then(response => {
                    this.post = response.data.data;
                });
        },
        store() {
            const formData = new FormData();
            formData.append('title', this.title);
            const images = this.dropzone.getAcceptedFiles();
            images.forEach(image => {
                formData.append('images[]', image);
                this.dropzone.removeFile(image);
            });
            axios.post('/api/post/store', formData)
                .then(response => {
                    this.title = '';
                });
        }
    }
}
</script>
