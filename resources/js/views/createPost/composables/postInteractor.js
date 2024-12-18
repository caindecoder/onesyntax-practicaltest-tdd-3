import PostGateway from './PostGateway';
import PostRequest from './PostRequest';
import Post from './Post';

export default class PostInteractor {
    constructor() {
        this.gateway = new PostGateway();
    }

    async fetchPosts() {
        return await this.gateway.fetchPosts();
    }

    async createPost(post) {
        const request = new PostRequest(post);
        request.validate();
        const createdPost = await this.gateway.createPost(request);
        return new Post(createdPost);
    }
}
