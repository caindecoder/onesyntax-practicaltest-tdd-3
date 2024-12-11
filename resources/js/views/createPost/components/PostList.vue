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
            <h3>{{ post.title }}</h3>
            <p>{{ post.description }}</p>
            <small>Website: {{ post.website_id }}</small>
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
