<script>
export default {
    name: 'NotFoundPage',
};
</script>

<template>
    <div class="not-found-page">
        <h1 class="error-title">404 - Page Not Found</h1>
        <p class="error-message">Sorry, the page you are looking for does not exist.</p>
        <router-link to="/" class="back-link">Go Back to Home</router-link>
    </div>
</template>


<style scoped>
.not-found-page {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    text-align: center;
}

.error-title {
    font-size: 2.5rem;
    color: red;
    margin-bottom: 1rem;
}

.error-message {
    font-size: 1.2rem;
    margin-bottom: 2rem;
}

.back-link {
    padding: 10px 20px;
    background-color: #4CAF50;
    color: white;
    text-decoration: none;
    border-radius: 5px;
}

.back-link:hover {
    background-color: #45a049;
}
</style>
