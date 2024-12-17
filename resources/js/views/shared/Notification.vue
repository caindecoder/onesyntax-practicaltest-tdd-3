<script>
export default {
    props: {
        message: {
            type: String,
            default: '',
        },
        type: {
            type: String,
            default: 'info',
        },
    },
    computed: {
        typeClass() {
            return this.type === 'success' ? 'notification-success' : 'notification-error';
        },
    },
};
</script>

<template>
    <div v-if="message" :class="['notification', typeClass]">
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
