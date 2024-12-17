import { PostGateway } from './PostGateway';
import { PostRequest } from './postRequest';

export async function postCreate(data) {
    const request = new PostRequest(data);
    request.validate();

    const gateway = new PostGateway();
    const response = await gateway.create({
        title: request.title,
        description: request.description,
        website_id: request.website_id,
    });

    return response;
}
