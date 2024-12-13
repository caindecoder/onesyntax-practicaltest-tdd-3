import { subscriptionCreate } from './subscriptionCreate';
import { subscriptionFetch } from './subscriptionFetch';

export class SubscriptionInteractor {
    async createSubscription(subscriptionRequest) {
        subscriptionRequest.validate();
        return await subscriptionCreate(subscriptionRequest);
    }

    async fetchSubscriptions() {
        return await subscriptionFetch();
    }
}
