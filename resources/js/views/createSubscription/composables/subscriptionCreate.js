import { SubscriptionGateway } from './SubscriptionGateway';
import { SubscriptionRequest } from './subscriptionRequest';

export async function subscriptionCreate(data) {
    const request = new SubscriptionRequest(data);
    request.validate();

    const gateway = new SubscriptionGateway();
    const response = await gateway.create({
        email: request.email,
        website_id: request.website_id,
    });

    return response;
}
