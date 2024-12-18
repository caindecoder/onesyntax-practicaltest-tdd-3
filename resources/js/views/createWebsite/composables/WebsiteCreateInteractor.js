import WebsiteGateway from './WebsiteGateway';
import WebsiteRequest from './WebsiteRequest';
import Website from './Website';

export default class CreateWebsiteInteractor {
    constructor() {
        this.gateway = new WebsiteGateway();
    }

    async execute(website) {
        const request = new WebsiteRequest(website);
        request.validate();
        const createdWebsite = await this.gateway.createWebsite(request);
        return new Website(createdWebsite);
    }
}
