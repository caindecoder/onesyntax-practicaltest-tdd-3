<script>
export default {
    props: {
        message: {
            type: String,
            required: true,
        },
        type: {
            type: String,
            default: 'success', // Can be 'success' or 'error'
        },
    },
};
</script>

<template>
    <div :class="['notification', type]">
        {{ message }}
    </div>
</template>

<style scoped>
.notification {
    padding: 10px;
    margin-bottom: 20px;
    border-radius: 5px;
    text-align: center;
    font-size: 1rem;
}
.notification.success {
    background-color: #4caf50;
    color: white;
}
.notification.error {
    background-color: #f44336;
    color: white;
}
</style>
