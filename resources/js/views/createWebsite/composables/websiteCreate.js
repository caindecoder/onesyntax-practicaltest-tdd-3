import apiClient from './apiClient';

export async function websiteCreate(websiteRequest) {
    const response = await apiClient.post('/websites', {
        name: websiteRequest.name,
        url: websiteRequest.url,
    });
    return response.data;
}
