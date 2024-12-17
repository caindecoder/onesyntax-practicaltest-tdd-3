import { SubscriptionGateway } from './SubscriptionGateway';

export async function subscriptionFetch() {
    const gateway = new SubscriptionGateway();
    return await gateway.fetchAll();
}
