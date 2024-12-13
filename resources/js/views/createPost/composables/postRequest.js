export class PostRequest {
    constructor({ title, description, websiteId }) {
        this.title = title;
        this.description = description;
        this.websiteId = websiteId;
    }

    validate() {
        const errors = [];
        if (!this.title) errors.push('Title is required.');
        if (!this.description) errors.push('Description is required.');
        if (!this.websiteId) errors.push('Website ID is required.');
        if (errors.length) throw new Error(errors.join(' '));
    }
}
