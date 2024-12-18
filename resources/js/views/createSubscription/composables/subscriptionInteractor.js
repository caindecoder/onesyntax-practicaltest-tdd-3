import SubscriptionGateway from './SubscriptionGateway';
import SubscriptionRequest from './SubscriptionRequest';

export default class SubscriptionInteractor {
    constructor() {
        this.gateway = new SubscriptionGateway();
    }

    async fetchSubscriptions() {
        return await this.gateway.fetchSubscriptions();
    }

    async createSubscription(subscriptionData) {
        const request = new SubscriptionRequest(subscriptionData);
        request.validate();
        return await this.gateway.createSubscription(request);
    }
}
