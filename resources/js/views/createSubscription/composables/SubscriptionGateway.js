export class SubscriptionGateway {
    async fetchSubscriptions() {
        const response = await fetch('/api/subscriptions');
        if (!response.ok) throw new Error('Failed to fetch subscriptions');
        return response.json();
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
