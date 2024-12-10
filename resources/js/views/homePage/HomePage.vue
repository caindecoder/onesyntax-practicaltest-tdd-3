<script>
export default {
    name: 'HomePage',
};
</script>

<template>
    <div class="home-page">
        <h1 class="main-title">Welcome to the Subscription Platform</h1>
        <p class="intro-text">Manage your websites, posts, and subscriptions with ease.</p>

        <div class="link-container">
            <router-link to="/website" class="link">Create a Website</router-link>
            <router-link to="/post" class="link">Create a Post</router-link>
            <router-link to="/subscription" class="link">Create a Subscription</router-link>
        </div>
    </div>
</template>


<style scoped>
.home-page {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    text-align: center;
}

.main-title {
    font-size: 2rem;
    margin-bottom: 1rem;
}

.intro-text {
    font-size: 1rem;
    margin-bottom: 2rem;
}

.link-container {
    display: flex;
    justify-content: space-around;
    margin-top: 2rem;
}

.link {
    padding: 10px 20px;
    background-color: #4CAF50;
    color: white;
    text-decoration: none;
    border-radius: 5px;
}

.link:hover {
    background-color: #45a049;
}
</style>
