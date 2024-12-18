import WebsiteGateway from './WebsiteGateway';
import WebsiteRequest from './websiteRequest';

export default class WebsiteInteractor {
    constructor() {
        this.gateway = new WebsiteGateway();
    }

    async fetchWebsites() {
        return await this.gateway.fetchWebsites();
    }

    async createWebsite(websiteData) {
        const request = new WebsiteRequest(websiteData);
        request.validate();
        return await this.gateway.createWebsite(request);
    }
}
