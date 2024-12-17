import apiClient from './apiClient';

export class PostGateway {
    async create(data) {
        const response = await apiClient.post('/posts', data);
        return response.data;
    }

    async fetchAll() {
        const response = await apiClient.get('/posts');
        return response.data;
    }
}
