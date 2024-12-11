
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
            post: {
                title: '',
                description: '',
                website_id: null,
            },
        };
    },
    emits: ['postCreated'],
    methods: {
        submitPost() {
            this.$emit('postCreated', { ...this.post });
            this.post = { title: '', description: '', website_id: null }; // Reset form
        },
    },
};
</script>

<template>
    <form @submit.prevent="submitPost" class="create-post-form">
        <div class="create-post-form">
            <label for="title">Title:</label>
            <input id="title" v-model="post.title" required/>
        </div>

        <div class="create-post-form">
            <label for="description">Description:</label>
            <textarea id="description" v-model="post.description" required></textarea>
        </div>

        <div class="create-post-form">
            <label for="website">Website:</label>
            <select id="website" v-model="post.website_id" required>
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
