import apiClient from './apiClient';

export async function websiteFetch() {
    const response = await apiClient.get('/websites');
    return response.data;
}
