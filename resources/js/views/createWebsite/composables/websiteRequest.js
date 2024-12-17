export class WebsiteRequest {
    constructor({ name, url }) {
        this.name = name;
        this.url = url;
    }

    validate() {
        const errors = [];
        if (!this.name) errors.push('Website name is required.');
        if (!this.url) errors.push('Website URL is required.');
        if (errors.length) throw new Error(errors.join(' '));
    }
}
