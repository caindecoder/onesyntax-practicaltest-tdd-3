<script>
import { useSubscription } from './composables/useSubscription.js';
import CreateSubscriptionForm from './components/CreateSubscriptionForm.vue';
import SubscriptionList from './components/SubscriptionList.vue';
import Notification from './components/Notification.vue';

export default {
    components: {
        CreateSubscriptionForm,
        SubscriptionList,
        Notification,
    },
    setup() {
        const {
            subscriptions,
            websites,
            message,
            messageType,
            fetchSubscriptions,
            fetchWebsites,
            createSubscription,
        } = useSubscription();

        // Load subscriptions and websites on component mount
        fetchSubscriptions();
        fetchWebsites();

        const handleSubscriptionCreated = (subscriptionData) => {
            createSubscription(subscriptionData);
        };

        return {
            subscriptions,
            websites,
            message,
            messageType,
            handleSubscriptionCreated,
        };
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
