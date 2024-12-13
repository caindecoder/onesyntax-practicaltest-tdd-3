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
            <router-link class="link" to="/website">Websites</router-link>
            <router-link class="link" to="/post">Posts</router-link>
            <router-link class="link" to="/subscription">Subscriptions</router-link>
        </div>
    </nav>
    <main>
        <router-view></router-view>
    </main>
</template>


<style lang="scss" scoped>

</style>
