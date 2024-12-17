import { websiteCreate } from './websiteCreate.js';
import {websiteFetch} from './websiteFetch.js';
import { Website } from './Website.js';

export class WebsiteInteractor {
    async createWebsite(data) {
        const response = await websiteCreate(data);
        return new Website(response);
    }

    async getWebsites() {
        const response = await websiteFetch();
        return response.map((item) => new Website(item));
    }
}
