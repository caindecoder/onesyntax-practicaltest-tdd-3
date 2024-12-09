<template>
    <div class="container">
        <h1>Create Subscription</h1>

        <div v-if="successMessage" class="alert alert-success">{{ successMessage }}</div>

        <form @submit.prevent="createSubscription">
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input
                    type="email"
                    class="form-control"
                    id="email"
                    v-model="form.email"
                    required
                />
            </div>

            <div class="mb-3">
                <label for="website_id" class="form-label">Select Website</label>
                <select
                    class="form-select"
                    id="website_id"
                    v-model="form.website_id"
                    required
                >
                    <option v-for="website in websites" :key="website.id" :value="website.id">
                        {{ website.name }}
                    </option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Subscribe</button>
        </form>

        <h2 class="mt-5">Existing Subscriptions</h2>
        <ul>
            <li v-for="subscription in subscriptions" :key="subscription.id">
                {{ subscription.email }} - {{ subscription.website.name }}
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
                email: "",
                website_id: null,
            },
            websites: [],
            subscriptions: [],
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
        async fetchSubscriptions() {
            try {
                const response = await axios.get("/api/subscriptions");
                this.subscriptions = response.data;
            } catch (error) {
                console.error("Error fetching subscriptions:", error);
            }
        },
        async createSubscription() {
            try {
                const response = await axios.post("/api/subscriptions", this.form);
                this.successMessage = "Subscription created successfully!";
                this.fetchSubscriptions();
                this.form.email = "";
                this.form.website_id = null;
            } catch (error) {
                console.error("Error creating subscription:", error);
            }
        },
    },
    created() {
        this.fetchWebsites();
        this.fetchSubscriptions();
    },
};
</script>

<style>
.container {
    max-width: 600px;
    margin: auto;
}
</style>
