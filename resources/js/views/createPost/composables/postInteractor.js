import { postCreate } from './postCreate';
import { postFetch } from './postFetch';
import { Post } from './Post';

export class PostInteractor {
    async createPost(data) {
        const response = await postCreate(data);
        return new Post(response);
    }

    async getPosts() {
        const response = await postFetch();
        return response.map((item) => new Post(item));
    }
}
