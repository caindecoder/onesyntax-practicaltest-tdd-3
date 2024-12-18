import WebsiteGateway from './WebsiteGateway';
import WebsiteRequest from './websiteRequest';
import Website from './Website';

export default class WebsiteInteractor {
    constructor() {
        this.gateway = new WebsiteGateway();
    }

    async fetchWebsites() {
        return await this.gateway.fetchWebsites();
    }

    async createWebsite(website) {
        const request = new WebsiteRequest(website);
        request.validate();
        const createdWebsite = await this.gateway.createWebsite(request);
        return new Website(createdWebsite);
    }
}
