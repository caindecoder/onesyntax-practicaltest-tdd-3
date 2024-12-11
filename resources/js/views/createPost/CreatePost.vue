<script>
import { usePost } from './composables/usePost.js';
import CreatePostForm from './components/CreatePostForm.vue';
import PostList from './components/PostList.vue';
import Notification from './components/Notification.vue';

export default {
    components: {
        CreatePostForm,
        PostList,
        Notification,
    },
    setup() {
        const { posts, websites, message, messageType, fetchPosts, fetchWebsites, createPost } = usePost();

        fetchPosts();
        fetchWebsites();

        const handlePostCreated = (postData) => {
            createPost(postData);
        };

        return {
            posts,
            websites,
            message,
            messageType,
            handlePostCreated,
        };
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
