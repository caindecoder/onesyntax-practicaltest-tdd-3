<template>
    <div class="container">
        <h1>Create Website</h1>

        <div v-if="successMessage" class="alert alert-success">{{ successMessage }}</div>

        <form @submit.prevent="createWebsite">
            <div class="mb-3">
                <label for="name" class="form-label">Website Name</label>
                <input
                    type="text"
                    class="form-control"
                    id="name"
                    v-model="form.name"
                    required
                />
            </div>

            <div class="mb-3">
                <label for="url" class="form-label">Website URL</label>
                <input
                    type="url"
                    class="form-control"
                    id="url"
                    v-model="form.url"
                    required
                />
            </div>

            <button type="submit" class="btn btn-primary">Create Website</button>
        </form>

        <h2 class="mt-5">Existing Websites</h2>
        <ul>
            <li v-for="website in websites" :key="website.id">
                {{ website.name }} - <a :href="website.url" target="_blank">{{ website.url }}</a>
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
                name: "",
                url: "",
            },
            websites: [],
            successMessage: "",
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
        async createWebsite() {
            try {
                const response = await axios.post("/api/websites", this.form);
                this.successMessage = "Website created successfully!";
                this.fetchWebsites(); // Refresh the website list
                this.form.name = "";
                this.form.url = "";
            } catch (error) {
                console.error("Error creating website:", error);
            }
        },
    },
    created() {
        this.fetchWebsites();
    },
};
</script>

<style>
.container {
    max-width: 600px;
    margin: auto;
}
</style>
