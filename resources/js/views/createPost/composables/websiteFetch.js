import { WebsiteGateway } from './WebsiteGateway';

export async function websiteFetch() {
    const gateway = new WebsiteGateway();
    return await gateway.fetchAll();
}
