import WebsiteGateway from './WebsiteGateway';

export default class FetchWebsitesInteractor {
    constructor() {
        this.gateway = new WebsiteGateway();
    }

    async execute() {
        return await this.gateway.fetchWebsites();
    }
}
