<script>
import { websiteFetch } from './composables/websiteFetch.js';
import { SubscriptionRequest } from './composables/subscriptionRequest.js';
import { SubscriptionInteractor } from './composables/subscriptionInteractor.js';
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
            messageType: '',
        };
    },
    async created() {
        this.interactor = new SubscriptionInteractor();
        try {
            this.websites = await websiteFetch();
            this.subscriptions = await this.interactor.fetchSubscriptions();
        } catch (error) {
            this.message = error.message;
            this.messageType = 'error';
        }
    },
    methods: {
        async handleSubscriptionCreated(subscriptionData) {
            try {
                const subscriptionRequest = new SubscriptionRequest(subscriptionData);
                const newSubscription = await this.interactor.createSubscription(subscriptionRequest);
                this.subscriptions.push(newSubscription);
                this.message = 'Subscription created successfully.';
                this.messageType = 'success';
            } catch (error) {
                this.message = error.message;
                this.messageType = 'error';
            }
        },
    },
};
</script>

<template>
    <div class="create-subscription">
        <h1>Create a Subscription</h1>
        <Notification :message="message" :type="messageType" />
        <CreateSubscriptionForm :websites="websites" @subscriptionCreated="handleSubscriptionCreated" />
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
