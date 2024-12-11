<script>
export default {
    props: {
        websites: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {
            subscription: {
                email: '',
                website_id: null,
            },
        };
    },
    emits: ['subscriptionCreated'],
    methods: {
        submitSubscription() {
            this.$emit('subscriptionCreated', { ...this.subscription });
            this.subscription = { email: '', website_id: null }; // Reset form
        },
    },
};
</script>

<template>
    <form @submit.prevent="submitSubscription" class="create-subscription-form">
        <div>
            <label for="email">Email:</label>
            <input id="email" type="email" v-model="subscription.email" required />
        </div>

        <div>
            <label for="website">Website:</label>
            <select id="website" v-model="subscription.website_id" required>
                <option v-for="website in websites" :key="website.id" :value="website.id">
                    {{ website.name }}
                </option>
            </select>
        </div>

        <button type="submit">Create Subscription</button>
    </form>
</template>

<style scoped>
.create-subscription-form {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 20px;
}
.create-subscription-form input,
.create-subscription-form select {
    padding: 10px;
    font-size: 1rem;
    border-radius: 5px;
    border: #1a202c 1px solid
}
.create-subscription-form button {
    padding: 10px;
    background-color: #4caf50;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 1rem;
}
</style>
