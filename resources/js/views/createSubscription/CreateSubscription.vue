<script>
import CreateSubscriptionForm from './components/CreateSubscriptionForm.vue';
import SubscriptionList from './components/SubscriptionList.vue';
import Notification from './components/Notification.vue';

export default {
    components: {
        CreateSubscriptionForm,
        SubscriptionList,
        Notification,
    },
    data() {
        return {
            subscriptions: [],
            websites: [],
            message: '',
            messageType: '', // 'success' or 'error'
        };
    },
    mounted() {
        this.fetchData();
    },
    methods: {
        async fetchData() {
            try {
                const [subscriptionsResponse, websitesResponse] = await Promise.all([
                    fetch('/api/subscriptions'),
                    fetch('/api/websites'),
                ]);

                this.subscriptions = await subscriptionsResponse.json();
                this.websites = await websitesResponse.json();
            } catch (error) {
                this.message = 'Error fetching data.';
                this.messageType = 'error';
            }
        },
        handleSubscriptionCreated(newSubscription) {
            this.subscriptions.push(newSubscription);
            this.message = 'Subscription created successfully.';
            this.messageType = 'success';
        },
    },
};
</script>

<template>
    <div class="create-subscription">
        <h1>Create a Subscription</h1>
        <Notification :message="message" :type="messageType" v-if="message" />

        <CreateSubscriptionForm :websites="websites" @subscriptionCreated="handleSubscriptionCreated" />

        <h2>Existing Subscriptions</h2>
        <SubscriptionList :subscriptions="subscriptions" />
    </div>
</template>

<style scoped>
.create-subscription {
    max-width: 800px;
    margin: 0 auto;
    text-align: center;
}
</style>
