import apiClient from './apiClient';

export async function subscriptionFetch() {
    const response = await apiClient.get('/subscriptions');
    return response.data;
}
