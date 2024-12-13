export class PostRequest {
    constructor({ title, description, website_id }) {
        this.title = title;
        this.description = description;
        this.websiteId = website_id;
    }

    validate() {
        const errors = [];
        if (!this.title) errors.push('Title is required.');
        if (!this.description) errors.push('Description is required.');
        if (!this.websiteId) errors.push('Website ID is required.');
        if (errors.length) throw new Error(errors.join(' '));
    }
}
