<template>
    <div class="container">
        <h1>Create New Post</h1>

        <div v-if="successMessage" class="alert alert-success">{{ successMessage }}</div>
        <div v-if="errors.length" class="alert alert-danger">
            <ul>
                <li v-for="error in errors" :key="error">{{ error }}</li>
            </ul>
        </div>

        <form @submit.prevent="createPost">
            <div class="form-group">
                <label for="title">Title</label>
                <input
                    type="text"
                    id="title"
                    v-model="form.title"
                    class="form-control"
                    required
                />
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea
                    id="description"
                    v-model="form.description"
                    class="form-control"
                    required
                ></textarea>
            </div>

            <div class="form-group">
                <label for="website_id">Website</label>
                <select
                    id="website_id"
                    v-model="form.website_id"
                    class="form-control"
                    required
                >
                    <option value="">Select a website</option>
                    <option v-for="website in websites" :key="website.id" :value="website.id">
                        {{ website.name }}
                    </option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Create Post</button>
        </form>

        <h2 class="mt-5">Existing Posts</h2>
        <ul>
            <li v-for="post in posts" :key="post.id">
                {{ post.title }} - {{ post.description }}
            </li>
        </ul>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            form: {
                title: "",
                description: "",
                website_id: "",
            },
            websites: [],
            posts: [],
            successMessage: "",
            errors: [],
        };
    },
    methods: {
        async fetchWebsites() {
            try {
                const response = await axios.get("/api/websites");
                this.websites = response.data;
            } catch (error) {
                console.error("Error fetching websites:", error);
            }
        },
        async fetchPosts() {
            try {
                const response = await axios.get("/api/posts");
                this.posts = response.data;
            } catch (error) {
                console.error("Error fetching posts:", error);
            }
        },
        async createPost() {
            try {
                const response = await axios.post("/api/posts", this.form);
                this.successMessage = "Post created successfully!";
                this.errors = [];
                this.fetchPosts(); // Refresh the post list
                this.form = { title: "", description: "", website_id: "" }; // Reset form
            } catch (error) {
                if (error.response && error.response.data.errors) {
                    this.errors = Object.values(error.response.data.errors).flat();
                } else {
                    console.error("Error creating post:", error);
                }
            }
        },
    },
    created() {
        this.fetchWebsites();
        this.fetchPosts();
    },
};
</script>

<style>
.container {
    max-width: 600px;
    margin: auto;
}
</style>
