import Post from "./Post.js";

export default class PostGateway {
    async fetchPosts() {
        const response = await fetch('/api/posts');
        if (!response.ok) throw new Error('Failed to fetch posts');
        const posts = await response.json();
        return posts.map(data => new Post(data));
    }

    async createPost(postRequest) {
        const response = await fetch('/api/posts', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(postRequest),
        });
        if (!response.ok) throw new Error('Failed to create post');
        return response.json();
    }
}
