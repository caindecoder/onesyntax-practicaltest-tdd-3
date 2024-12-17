export class SubscriptionRequest {
    constructor({ email, website_id }) {
        this.email = email;
        this.website_id = website_id;
    }

    validate() {
        const errors = [];
        if (!this.email) errors.push('Email is required.');
        if (!this.website_id) errors.push('Website selection is required.');
        if (!/^\S+@\S+\.\S+$/.test(this.email)) errors.push('Invalid email format.');
        if (errors.length) throw new Error(errors.join(' '));
    }
}
