<template>
    <div class="container">
        <input type="text" v-model="title" required class="form-control mt-2" placeholder="Enter title...">
        <div ref="dropzone" class="btn d-block bg-dark mt-2 text-center text-light">
            Upload images
        </div>
        <input @click.prevent="update()" type="submit" class="btn btn-success mt-2" value="Update post">
        <div class="mt-5">
            <vue-editor useCustomImageHandler @image-removed="handleImageRemoved" @image-added="handleImageAdded" v-model="content" />
        </div>
        <div class="mt-5" v-if="post">
            <h5>{{ post.title }}</h5>
            <div class="ql-editor" v-html="post.content"></div>
            <div class="mt-2" v-for="image in post.images" :key="image.id">
                <img :src="image.url" class="w-25" :alt="post.title">
                <img :src="image.preview_url" class="w-10" :alt="post.title">
            </div>
            <hr>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import Dropzone from 'dropzone';
import { VueEditor } from 'vue3-editor';

export default {
    name: 'IndexComponent',
    data() {
        return {
            post: null,
            title: '',
            content: '',
            dropzone: null,
            deleteImageIds: [],
            deleteImageUrls: [],
        }
    },
    mounted() {
        this.dropzone = new Dropzone(this.$refs.dropzone, {
            url: '/api/post/store',
            autoProcessQueue: false,
            addRemoveLinks: true
        });
        this.dropzone.on('removedfile', file => {
            this.deleteImageIds.push(file.id);
        });
        this.getPost();
    },
    methods: {
        getPost() {
            axios.get('/api/post')
                .then(response => {
                    this.post = response.data.data;
                    this.title = this.post.title;
                    this.content = this.post.content;

                    this.post.images.forEach(image => {
                        let file = { id: image.id, name: image.name, size: image.size };
                        this.dropzone.displayExistingFile(file, image.preview_url);
                    });
                });
        },
        update() {
            const formData = new FormData();
            const images = this.dropzone.getAcceptedFiles();
            formData.append('title', this.title);
            formData.append('content', this.content);
            this.deleteImageIds.forEach(imageId => {
                formData.append('deleteImageIds[]', imageId);
            });
            this.deleteImageUrls.forEach(imageUrl => {
                formData.append('deleteImageUrls[]', imageUrl);
            });
            images.forEach(image => {
                formData.append('images[]', image);
                this.dropzone.removeFile(image);
            });
            formData.append('_method', 'PATCH');
            axios.post(`/api/post/update/${this.post.id}`, formData)
                .then(response => {
                    this.getPost();
                    let previews = this.dropzone.previewsContainer.querySelectorAll('.dz-image-preview');
                    previews.forEach(preview => {
                        preview.remove();
                    });
                });
        },
        handleImageAdded: (file, Editor, cursorLocation, resetUploader) => {
            const formData = new FormData();
            formData.append("image", file);

            axios.post("/api/image/store", formData)
                .then(result => {
                    const url = result.data.url;
                    Editor.insertEmbed(cursorLocation, "image", url);
                    resetUploader();
                });
        },
        handleImageRemoved(url) {
            this.deleteImageUrls.push(url);
        }
    },
    components: {
        VueEditor
    }
}
</script>

<style>
    .dz-success-mark,
    .dz-error-mark {
        display: none;
    }
</style>
