
<script>
export default {
    props: {
        posts: Array,
    },
};
</script>

<template>
    <div class="post-list">
        <h3>Posts</h3>
        <ul>
            <li v-for="post in posts" :key="post.id">
                <strong>{{ post.title }}</strong>: {{ post.description }}
            </li>
        </ul>
    </div>
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
