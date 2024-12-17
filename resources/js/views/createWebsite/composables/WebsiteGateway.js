import apiClient from './apiClient';

export class  WebsiteGateway {
    async create(data) {
        const response = await apiClient.post('/websites', data);
        return response.data;
    }

    async fetchAll() {
        const response = await apiClient.get('/websites');
        return response.data;
    }
}
