<script>
export default {
    data() {
        return {
            formData: {
                name: '',
                url: '',
            },
        };
    },
    methods: {
        submitForm() {
            this.$emit('websiteCreated', { ...this.formData });
        },
    },
};
</script>

<template>
    <form @submit.prevent="submitForm" class="create-website-form">
        <div>
            <label for="name">Website Name:</label>
            <input v-model="formData.name" id="name" type="text" required />
        </div>
        <div>
            <label for="url">Website URL:</label>
            <input v-model="formData.url" id="url" type="url" required />
        </div>
        <button type="submit">Create Website</button>
    </form>
</template>

<style scoped>
.create-website-form {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 20px;
}
.create-website-form input {
    padding: 10px;
    font-size: 1rem;
    border-radius: 5px;
    border: #1a202c 1px solid;
}
.create-website-form button {
    padding: 10px;
    background-color: #4caf50;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 1rem;
}
</style>
