<script>
import axios from 'axios';

export default {
    data() {
        return {
            website: {
                name: '',
                url: '',
            },
            websites: [],
        };
    },
    methods: {
        async fetchWebsites() {
            try {
                let response = await axios.get('/api/websites');
                this.websites = response.data// Fetch the list of websites from the API
            } catch (error) {
                console.error('Error fetching websites:', error);
            }
        },
        async submitWebsite() {
            try {
                let response = await axios.post('/api/websites', this.website);
                console.log(response);
                // Add the newly created website to the list and reset the form
                this.website.name = '';
                this.website.url = '';
                this.websites = response.data
                // await this.fetchWebsites();// Clear form fields
            } catch (error) {
                console.error('Error creating website:', error);
            }
        },
    },
    mounted() {
        this.fetchWebsites(); // Fetch the websites when the component is mounted
    },
};
</script>

<template>
    <div class="create-website">
        <!-- Header -->
        <h1 class="title">Create a New Website</h1>

        <!-- Form Section -->
        <form @submit.prevent="submitWebsite" class="form" method="post">
            <div class="form-group">
                <label for="name">Website Name:</label>
                <input v-model="website.name" id="name" type="text" required class="input" />
            </div>
            <div class="form-group">
                <label for="url">Website URL:</label>
                <input v-model="website.url" id="url" type="url" required class="input" />
            </div>
            <button type="submit" class="button">Create Website</button>
        </form>

        <!-- List Section -->
        <div class="website-list">
            <h2 class="subtitle">Existing Websites</h2>
            <ul>
                <li v-for="site in websites" :key="site.id" class="website-item">
                    <strong>{{ site.name }}</strong> - <a :href="site.url" target="_blank">{{ site.url }}</a>
                </li>
            </ul>
        </div>
    </div>
</template>

<style scoped>
.create-website {
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

.input {
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

.website-list {
    margin-top: 2rem;
}

.subtitle {
    font-size: 1.25rem;
    margin-bottom: 1rem;
    text-align: center;
}

.website-item {
    margin: 0.5rem 0;
}
</style>
