<script>
export default {
    props: ['subscriptions'],
};
</script>

<template>
    <ul class="subscription-list">
        <li v-for="subscription in subscriptions" :key="subscription.id">
            {{ subscription.email }} - {{ subscription.website.name }}
        </li>
    </ul>
</template>

<style scoped>
.subscription-list {
    list-style: none;
    padding: 0;
}
.subscription-list li {
    margin: 15px 0;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}
</style>
