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
            formData: {
                title: '',
                description: '',
                website_id: '',
            },
        };
    },
    methods: {
        submitForm() {
            this.$emit('postCreated', this.formData);
        },
    },
};
</script>

<template>
    <form @submit.prevent="submitForm" class="create-post-form">
        <div>
            <label for="title">Title:</label>
            <input v-model="formData.title" id="title" type="text" required />
        </div>
        <div>
            <label for="content">Content:</label>
            <textarea v-model="formData.description" id="content" required></textarea>
        </div>
        <div>
            <label for="website">Select Website:</label>
            <select v-model="formData.website_id" id="website" required>
                <option disabled value="">Select...</option>
                <option v-for="website in websites" :key="website.id" :value="website.id">
                    {{ website.name }}
                </option>
            </select>
        </div>
        <button type="submit">Create Post</button>
    </form>
</template>

<style scoped>
.create-post-form {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 20px;
}
.create-post-form input,
.create-post-form textarea,
.create-post-form select {
    padding: 10px;
    font-size: 1rem;
    border-radius: 5px;
    border: #1a202c 1px solid
}
.create-post-form button {
    padding: 10px;
    background-color: #4caf50;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 1rem;
}
</style>
