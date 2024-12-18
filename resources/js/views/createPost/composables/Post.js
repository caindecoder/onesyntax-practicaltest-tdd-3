export default class Post {
    constructor({ id, title, description, website_id }) {
        this.id = id || null;
        this.title = title || '';
        this.description = description || '';
        this.website_id = website_id || null;
    }
}
