export default class WebsiteGateway {
    async fetchWebsites() {
        const response = await fetch('/api/websites');
        if (!response.ok) throw new Error('Failed to fetch websites');
        return response.json();
    }

    async createWebsite(websiteRequest) {
        const response = await fetch('/api/websites', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(websiteRequest),
        });
        if (!response.ok) throw new Error('Failed to create website');
        return response.json();
    }
}
