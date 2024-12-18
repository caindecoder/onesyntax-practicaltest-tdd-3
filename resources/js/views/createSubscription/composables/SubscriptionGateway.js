import Subscription from "./Subscription.js";

export default class SubscriptionGateway {
    async fetchSubscriptions() {
        const response = await fetch('/api/subscriptions');
        if (!response.ok) throw new Error('Failed to fetch subscriptions');
        const subscriptions = await response.json();
        return subscriptions.map(data => new Subscription(data));
    }

    async createSubscription(subscriptionRequest) {
        const response = await fetch('/api/subscriptions', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(subscriptionRequest),
        });
        if (!response.ok) throw new Error('Failed to create subscription');
        return response.json();
    }
}
