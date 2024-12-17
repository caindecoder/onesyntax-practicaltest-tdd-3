<script>
export default {
    props: {
        subscriptions: {
            type: Array,
            required: true,
        },
    },
};
</script>

<template>
    <ul class="subscription-list">
        <li v-for="subscription in subscriptions" :key="subscription.id">
            {{ subscription.email }} subscribed to Website ID: {{ subscription.website_id }}
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
