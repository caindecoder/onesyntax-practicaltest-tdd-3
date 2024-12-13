import apiClient from './apiClient';

export async function postFetch() {
    const response = await apiClient.get('/posts');
    return response.data;
}
