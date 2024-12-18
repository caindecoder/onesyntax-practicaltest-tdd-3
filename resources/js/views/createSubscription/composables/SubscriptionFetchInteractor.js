import SubscriptionGateway from './SubscriptionGateway.js';

export default class SubscriptionFetchInteractor {
    constructor() {
        this.gateway = new SubscriptionGateway();
    }

    async execute() {
        return await this.gateway.fetchSubscriptions();
    }
}
