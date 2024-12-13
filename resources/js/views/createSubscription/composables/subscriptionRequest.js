export class SubscriptionRequest {
    constructor({ website_id, email }) {
        this.website_id = website_id;
        this.email = email;
    }

    validate() {
        const errors = [];
        if (!this.email) errors.push('Email is required.');
        if (!this.website_id) errors.push('Website is required.');
        if (errors.length) throw new Error(errors.join(' '));
    }
}
