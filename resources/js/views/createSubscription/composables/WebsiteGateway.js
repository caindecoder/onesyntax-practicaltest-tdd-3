export default class WebsiteGateway {
    async fetchWebsites() {
        const response = await fetch('/api/websites');
        if (!response.ok) throw new Error('Failed to fetch websites');
        return response.json();
    }
}
