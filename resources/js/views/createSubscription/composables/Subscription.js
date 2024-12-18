export default class Subscription {
    constructor({ id, email, website_id }) {
        this.id = id || null;
        this.email = email || '';
        this.website_id = website_id || null;
    }
}
