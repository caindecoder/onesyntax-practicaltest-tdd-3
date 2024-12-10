<script>
import axios from 'axios';

export default {
    data() {
        return {
            subscription: {
                email: '',
                website_id: null,
            },
            subscriptions: [],
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
        async fetchSubscriptions() {
            try {
                const response = await axios.get('/api/subscriptions'); // Update with your API endpoint
                this.subscriptions = response.data;
            } catch (error) {
                console.error('Error fetching subscriptions:', error);
            }
        },
        async submitSubscription() {
            try {
                const response = await axios.post('/api/subscriptions', this.subscription); // Update with your API endpoint
                this.subscriptions.push(response.data); // Add the new subscription to the list
                this.subscription.email = '';
                this.subscription.website_id = null; // Clear form fields
            } catch (error) {
                console.error('Error creating subscription:', error);
            }
        },
        getWebsiteName(websiteId) {
            const website = this.websites.find((site) => site.id === websiteId);
            return website ? website.name : 'Unknown';
        },
    },
    mounted() {
        this.fetchWebsites();
        this.fetchSubscriptions();
    },
};
</script>

<template>
    <div class="create-subscription">
        <!-- Header -->
        <h1 class="title">Create a New Subscription</h1>

        <!-- Form Section -->
        <form @submit.prevent="submitSubscription" class="form">
            <div class="form-group">
                <label for="email">Email:</label>
                <input v-model="subscription.email" id="email" type="email" required class="input" />
            </div>

            <div class="form-group">
                <label for="website_id">Website:</label>
                <select v-model="subscription.website_id" id="website_id" required class="select">
                    <option v-for="site in websites" :key="site.id" :value="site.id">{{ site.name }}</option>
                </select>
            </div>

            <button type="submit" class="button">Create Subscription</button>
        </form>

        <!-- List Section -->
        <div class="subscription-list">
            <h2 class="subtitle">Existing Subscriptions</h2>
            <ul>
                <li v-for="sub in subscriptions" :key="sub.id" class="subscription-item">
                    <strong>{{ sub.email }}</strong> (Website: {{ getWebsiteName(sub.website_id) }})
                </li>
            </ul>
        </div>
    </div>
</template>



<style scoped>
.create-subscription {
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

.subscription-list {
    margin-top: 2rem;
}

.subtitle {
    font-size: 1.25rem;
    margin-bottom: 1rem;
    text-align: center;
}

.subscription-item {
    margin: 0.5rem 0;
}
</style>
