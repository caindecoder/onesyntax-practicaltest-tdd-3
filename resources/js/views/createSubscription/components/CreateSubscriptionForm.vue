<script>
export default {
    props: ['websites'],
    data() {
        return {
            formData: {
                website_id: '',
                email: '',
            },
        };
    },
    methods: {
        submitForm() {
            this.$emit('subscriptionCreated', this.formData);
        },
    },
};
</script>

<template>
    <form @submit.prevent="submitForm" class="create-subscription-form">
        <div>
            <label for="website_id">Website:</label>
            <select v-model="formData.website_id" id="website_id" required>
                <option v-for="website in websites" :key="website.id" :value="website.id">
                    {{ website.name }}
                </option>
            </select>
        </div>
        <div>
            <label for="email">Email:</label>
            <input v-model="formData.email" id="email" type="email" required />
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
