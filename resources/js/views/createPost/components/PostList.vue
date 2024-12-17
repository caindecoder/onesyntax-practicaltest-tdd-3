<script>
export default {
    props: {
        posts: {
            type: Array,
            required: true,
        },
    },
};
</script>

<template>
    <ul class="post-list">
        <li v-for="post in posts" :key="post.id">
            <strong>{{ post.title }}</strong> - {{ post.description }} (Website ID: {{ post.website_id }})
        </li>
    </ul>
</template>

<style scoped>
.post-list {
    list-style: none;
    padding: 0;
}
.post-list li {
    margin: 15px 0;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}
</style>
