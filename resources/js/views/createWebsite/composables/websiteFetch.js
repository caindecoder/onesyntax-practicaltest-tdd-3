import { WebsiteGateway} from './WebsiteGateway.js';

export async function websiteFetch() {
    const websiteGateway = new WebsiteGateway();
    return await websiteGateway.fetchAll();
}
