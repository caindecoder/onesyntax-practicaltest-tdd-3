import PostGateway from './PostGateway';
import PostRequest from './PostRequest';

export default class PostInteractor {
    constructor() {
        this.gateway = new PostGateway();
    }

    async fetchPosts() {
        return await this.gateway.fetchPosts();
    }

    async createPost(postData) {
        const request = new PostRequest(postData);
        request.validate();
        return await this.gateway.createPost(request);
    }
}
