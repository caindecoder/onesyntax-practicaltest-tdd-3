import apiClient from './apiClient';

export async function subscriptionCreate(subscriptionRequest) {
    const response = await apiClient.post('/subscriptions', {
        website_id: subscriptionRequest.website_id,
        email: subscriptionRequest.email,
    });
    return response.data;
}
