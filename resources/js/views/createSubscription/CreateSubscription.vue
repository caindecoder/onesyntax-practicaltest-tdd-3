<script>
import { SubscriptionInteractor } from './composables/subscriptionInteractor.js';
import { WebsiteGateway } from './composables/WebsiteGateway.js';
import CreateSubscriptionForm from './components/CreateSubscriptionForm.vue';
import SubscriptionList from './components/SubscriptionList.vue';
import Notification from '../shared/Notification.vue';

export default {
    components: {
        CreateSubscriptionForm,
        SubscriptionList,
        Notification,
    },
    data() {
        return {
            websites: [],
            subscriptions: [],
            message: '',
            messageType: '',
        };
    },
    async created() {
        this.subscriptionInteractor = new SubscriptionInteractor();
        this.websiteGateway = new WebsiteGateway();

        await this.loadWebsites();
        await this.loadSubscriptions();
    },
    methods: {
        async loadWebsites() {
            try {
                this.websites = await this.websiteGateway.fetchWebsites();
            } catch (error) {
                this.message = error.message;
                this.messageType = 'error';
            }
        },
        async loadSubscriptions() {
            try {
                this.subscriptions = await this.subscriptionInteractor.fetchSubscriptions();
            } catch (error) {
                this.message = error.message;
                this.messageType = 'error';
            }
        },
        async handleSubscriptionCreated(subscriptionData) {
            try {
                const newSubscription = await this.subscriptionInteractor.createSubscription(subscriptionData);
                this.subscriptions.push(newSubscription);
                this.message = 'Subscription created successfully!';
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
