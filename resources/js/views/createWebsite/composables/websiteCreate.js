import { WebsiteGateway } from './WebsiteGateway.js';
import { WebsiteRequest } from './websiteRequest.js';

export async function websiteCreate(data) {
    const request = new WebsiteRequest(data);
    request.validate();

    const gateway = new WebsiteGateway();
    const response = await gateway.create({
        name: request.name,
        url: request.url,
    });
}
