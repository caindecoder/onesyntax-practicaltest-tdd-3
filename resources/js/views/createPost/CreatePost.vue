<script>
import { PostRequest } from './composables/postRequest.js';
import { PostInteractor } from './composables/postInteractor.js';
import { websiteFetch } from './composables/websiteFetch.js';
import CreatePostForm from './components/CreatePostForm.vue';
import PostList from './components/PostList.vue';
import Notification from './components/Notification.vue';

export default {
    components: {
        CreatePostForm,
        PostList,
        Notification,
    },
    data() {
        return {
            posts: [],
            websites: [],
            message: '',
            messageType: '',
        };
    },
    async created() {
        this.interactor = new PostInteractor();
        try {
            this.websites = await websiteFetch();
            this.posts = await this.interactor.fetchPosts();
        } catch (error) {
            this.message = error.message;
            this.messageType = 'error';
        }
    },
    methods: {
        async handlePostCreated(postData) {
            try {
                const postRequest = new PostRequest(postData);
                const newPost = await this.interactor.createPost(postRequest);
                this.posts.push(newPost);
                this.message = 'Post created successfully.';
                this.messageType = 'success';
            } catch (error) {
                this.message = error.message;
                this.messageType = 'error';
            }
        },
    },
};
</script>



<template>
    <div class="create-post">
        <h1>Create a Post</h1>
        <Notification :message="message" :type="messageType" v-if="message" />

        <CreatePostForm :websites="websites" @postCreated="handlePostCreated" />

        <h2>Existing Posts</h2>
        <PostList :posts="posts" />
    </div>
</template>

<style scoped>
.create-post {
    max-width: 800px;
    margin: 0 auto;
    text-align: center;
}
</style>
