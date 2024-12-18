export default class SubscriptionRequest {
    constructor({ email, website_id }) {
        this.email = email;
        this.website_id = website_id;
    }

    validate() {
        const errors = [];
        if (!this.email) errors.push('Email is required.');
        if (!this.email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) errors.push('Invalid email format.');
        if (!this.website_id) errors.push('Website selection is required.');
        if (errors.length) throw new Error(errors.join(' '));
    }
}
