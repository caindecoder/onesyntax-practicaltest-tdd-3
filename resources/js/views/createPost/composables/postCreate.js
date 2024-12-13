import apiClient from './apiClient';

export async function postCreate(postRequest) {
    const response = await apiClient.post('/posts', {
        title: postRequest.title,
        description: postRequest.description,
        website_id: postRequest.websiteId,
    });
    return response.data;
}
