<script>
import axios from 'axios';

export default {
    data() {
        return {
            post: {
                title: '',
                description: '',
                website_id: null,
            },
            posts: [],
            websites: [],
        };
    },
    methods: {
        async fetchWebsites() {
            try {
                const response = await axios.get('/api/websites'); // Update with your API endpoint
                this.websites = response.data;
            } catch (error) {
                console.error('Error fetching websites:', error);
            }
        },
        async fetchPosts() {
            try {
                const response = await axios.get('/api/posts'); // Update with your API endpoint
                this.posts = response.data;
            } catch (error) {
                console.error('Error fetching posts:', error);
            }
        },
        async submitPost() {
            try {
                const response = await axios.post('/api/posts', this.post); // Update with your API endpoint
                this.posts.push(response.data); // Add the new post to the list
                this.post.title = '';
                this.post.description = '';
                this.post.website_id = null; // Clear form fields
            } catch (error) {
                console.error('Error creating post:', error);
            }
        },
        getWebsiteName(websiteId) {
            const website = this.websites.find((site) => site.id === websiteId);
            return website ? website.name : 'Unknown';
        },
    },
    mounted() {
        this.fetchWebsites();
        this.fetchPosts();
    },
};
</script>

<template>
    <div class="create-post">
        <!-- Header -->
        <h1 class="title">Create a New Post</h1>

        <!-- Form Section -->
        <form @submit.prevent="submitPost" class="form">
            <div class="form-group">
                <label for="title">Post Title:</label>
                <input v-model="post.title" id="title" type="text" required class="input" />
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea v-model="post.description" id="description" required class="textarea"></textarea>
            </div>

            <div class="form-group">
                <label for="website_id">Website:</label>
                <select v-model="post.website_id" id="website_id" required class="select">
                    <option v-for="site in websites" :key="site.id" :value="site.id">{{ site.name }}</option>
                </select>
            </div>

            <button type="submit" class="button">Create Post</button>
        </form>

        <!-- List Section -->
        <div class="post-list">
            <h2 class="subtitle">Existing Posts</h2>
            <ul>
                <li v-for="p in posts" :key="p.id" class="post-item">
                    <strong>{{ p.title }}</strong>: {{ p.description }}
                    <em>(Website: {{ getWebsiteName(p.website_id) }})</em>
                </li>
            </ul>
        </div>
    </div>
</template>



<style scoped>
.create-post {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
}

.title {
    font-size: 1.5rem;
    margin-bottom: 1rem;
    text-align: center;
}

.form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 2rem;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.input,
.textarea,
.select {
    padding: 0.5rem;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.button {
    padding: 0.5rem;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.button:hover {
    background-color: #45a049;
}

.post-list {
    margin-top: 2rem;
}

.subtitle {
    font-size: 1.25rem;
    margin-bottom: 1rem;
    text-align: center;
}

.post-item {
    margin: 0.5rem 0;
}
</style>
