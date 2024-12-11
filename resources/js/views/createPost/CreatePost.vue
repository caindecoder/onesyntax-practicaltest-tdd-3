<script>
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
            messageType: '', // 'success' or 'error'
        };
    },
    mounted() {
        this.fetchData();
    },
    methods: {
        async fetchData() {
            try {
                const [postsResponse, websitesResponse] = await Promise.all([
                    fetch('/api/posts'),
                    fetch('/api/websites'),
                ]);

                this.posts = await postsResponse.json();
                this.websites = await websitesResponse.json();
            } catch (error) {
                this.message = 'Error fetching data.';
                this.messageType = 'error';
            }
        },
        handlePostCreated(newPost) {
            this.posts.push(newPost);
            this.message = 'Post created successfully.';
            this.messageType = 'success';
        },
    },
};
</script>

<template>
    <div class="create-post">
        <h1>Create a New Post</h1>
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
