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
            email: '',
            websiteId: '',
        };
    },
    methods: {
        async submitForm() {
            try {
                const response = await fetch('/api/subscriptions', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        email: this.email,
                        website_id: this.websiteId,
                    }),
                });

                const data = await response.json();
                if (response.ok) {
                    this.$emit('subscriptionCreated', data.subscription);
                    this.email = '';
                    this.websiteId = '';
                } else {
                    alert(`Error: ${data.error}`);
                }
            } catch (error) {
                console.error('Error creating subscription:', error);
            }
        },
    },
};
</script>

<template>
    <form @submit.prevent="submitForm" class="create-subscription-form">
        <input v-model="email" type="email" placeholder="Email Address" required />
        <select v-model="websiteId" required>
            <option value="" disabled selected>Select a Website</option>
            <option v-for="website in websites" :key="website.id" :value="website.id">
                {{ website.name }}
            </option>
        </select>
        <button type="submit">Subscribe</button>
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
