import apiClient from './apiClient';

export class SubscriptionGateway {
    async create(data) {
        const response = await apiClient.post('/subscriptions', data);
        return response.data;
    }

    async fetchAll() {
        const response = await apiClient.get('/subscriptions');
        return response.data;
    }
}
