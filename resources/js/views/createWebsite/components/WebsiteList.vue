<script>
export default {
    props: {
        websites: {
            type: Array,
            required: true,
        },
    },
};
</script>

<template>
    <ul class="website-list">
        <li v-for="website in websites" :key="website.id">
            {{ website.name }} - {{ website.url }}
        </li>
    </ul>
</template>

<style scoped>
.website-list {
    list-style: none;
    padding: 0;
}
.website-list li {
    margin: 10px 0;
    font-size: 1.1rem;
}
</style>
