import { websiteCreate } from './websiteCreate';
import { websiteFetch } from './websiteFetch';

export class WebsiteInteractor {
    async createWebsite(websiteRequest) {
        websiteRequest.validate();
        return await websiteCreate(websiteRequest);
    }

    async fetchWebsites() {
        return await websiteFetch();
    }
}
