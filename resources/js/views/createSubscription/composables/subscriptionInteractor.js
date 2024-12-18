import SubscriptionGateway from './SubscriptionGateway';
import SubscriptionRequest from './SubscriptionRequest';
import Subscription from './Subscription';

export default class SubscriptionInteractor {
    constructor() {
        this.gateway = new SubscriptionGateway();
    }

    async fetchSubscriptions() {
        return await this.gateway.fetchSubscriptions();
    }

    async createSubscription(subscription) {
        const request = new SubscriptionRequest(subscription);
        request.validate();
        const createdSubscription = await this.gateway.createSubscription(request);
        return new Subscription(createdSubscription);
    }
}
