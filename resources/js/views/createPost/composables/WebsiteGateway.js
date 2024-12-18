import Website from "../composables/Post.js";

export default class WebsiteGateway {
    async fetchWebsites() {
        const response = await fetch('/api/websites');
        if (!response.ok) throw new Error('Failed to fetch websites');
        const websites = await response.json();
        return websites.map(data => new Website(data));
    }
}
