import { postCreate } from './postCreate';
import { postFetch } from './postFetch';

export class PostInteractor {
    async createPost(postRequest) {
        postRequest.validate();
        return await postCreate(postRequest);
    }

    async fetchPosts() {
        return await postFetch();
    }
}
