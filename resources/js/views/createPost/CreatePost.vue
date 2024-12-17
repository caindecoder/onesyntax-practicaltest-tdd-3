<script>
import { PostInteractor } from './composables/postInteractor.js';
import { WebsiteGateway } from './composables/WebsiteGateway.js';
import CreatePostForm from './components/CreatePostForm.vue';
import PostList from './components/PostList.vue';
import Notification from '../shared/Notification.vue';

export default {
    components: {
        CreatePostForm,
        PostList,
        Notification,
    },
    data() {
        return {
            websites: [],
            posts: [],
            message: '',
            messageType: '',
        };
    },
    async created() {
        this.postInteractor = new PostInteractor();
        this.websiteGateway = new WebsiteGateway();

        await this.loadWebsites();
        await this.loadPosts();
    },
    methods: {
        async loadWebsites() {
            try {
                this.websites = await this.websiteGateway.fetchWebsites();
            } catch (error) {
                this.message = error.message;
                this.messageType = 'error';
            }
        },
        async loadPosts() {
            try {
                this.posts = await this.postInteractor.fetchPosts();
            } catch (error) {
                this.message = error.message;
                this.messageType = 'error';
            }
        },
        async handlePostCreated(postData) {
            try {
                const newPost = await this.postInteractor.createPost(postData);
                this.posts.push(newPost);
                this.message = 'Post created successfully!';
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
        <Notification :message="message" :type="messageType" />
        <CreatePostForm :websites="websites" @postCreated="handlePostCreated" />
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
