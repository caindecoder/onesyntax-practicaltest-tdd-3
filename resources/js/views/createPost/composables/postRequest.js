export default class PostRequest {
    constructor({ title, description, website_id }) {
        this.title = title;
        this.description = description;
        this.website_id = website_id;
    }

    validate() {
        const errors = [];
        if (!this.title) errors.push('Title is required.');
        if (!this.description) errors.push('Description is required.');
        if (!this.website_id) errors.push('Website selection is required.');
        if (errors.length) throw new Error(errors.join(' '));
    }
}
