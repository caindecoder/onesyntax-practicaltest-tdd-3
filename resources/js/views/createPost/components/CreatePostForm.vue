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
            title: '',
            description: '',
            websiteId: '',
        };
    },
    methods: {
        async submitForm() {
            try {
                const response = await fetch('/api/posts', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        title: this.title,
                        description: this.description,
                        website_id: this.websiteId,
                    }),
                });

                const data = await response.json();
                if (response.ok) {
                    this.$emit('postCreated', data.post);
                    this.title = '';
                    this.description = '';
                    this.websiteId = '';
                } else {
                    alert(`Error: ${data.error}`);
                }
            } catch (error) {
                console.error('Error creating post:', error);
            }
        },
    },
};
</script>

<template>
    <form @submit.prevent="submitForm" class="create-post-form">
        <input v-model="title" type="text" placeholder="Post Title" required />
        <textarea v-model="description" placeholder="Post Description" required></textarea>
        <select v-model="websiteId" required>
            <option value="" disabled selected>Select a Website</option>
            <option v-for="website in websites" :key="website.id" :value="website.id">
                {{ website.name }}
            </option>
        </select>
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
