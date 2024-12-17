import { subscriptionCreate } from './subscriptionCreate';
import { subscriptionFetch } from './subscriptionFetch';
import { Subscription } from './Subscription';

export class SubscriptionInteractor {
    async createSubscription(data) {
        const response = await subscriptionCreate(data);
        return new Subscription(response);
    }

    async getSubscriptions() {
        const response = await subscriptionFetch();
        return response.map((item) => new Subscription(item));
    }
}
