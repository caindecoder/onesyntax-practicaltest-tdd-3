<script>
export default {
    data() {
        return {
            name: '',
            url: '',
        };
    },
    methods: {
        async submitForm() {
            try {
                const response = await fetch('/api/websites', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ name: this.name, url: this.url }),
                });

                const data = await response.json();
                if (response.ok) {
                    this.$emit('websiteCreated', data.website);
                    this.name = '';
                    this.url = '';
                } else {
                    alert(`Error: ${data.error}`);
                }
            } catch (error) {
                console.error('Error creating website:', error);
            }
        },
    },
};
</script>

<template>
    <form @submit.prevent="submitForm" class="create-website-form">
        <input v-model="name" type="text" placeholder="Website Name" required />
        <input v-model="url" type="url" placeholder="Website URL" required />
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
