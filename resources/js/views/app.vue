<script setup>
</script>

<template>
    <h1>Subscription Platform</h1>
    <p>
        <strong>Current Route Path:</strong> {{ $route.fullPath }}
    </p>
    <nav>
        <div>
            <router-link class="link" to="/">Home Page</router-link>
            <router-link class="link" to="/website">Create Website</router-link>
            <router-link class="link" to="/post">Create Post</router-link>
            <router-link class="link" to="/subscription">Create Subscription</router-link>
        </div>
    </nav>
    <main>
        <router-view></router-view>
    </main>
</template>


<style lang="scss" scoped>

</style>
