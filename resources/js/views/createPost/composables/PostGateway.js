export class PostGateway {
    async fetchPosts() {
        const response = await fetch('/api/posts');
        if (!response.ok) throw new Error('Failed to fetch posts');
        return response.json();
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
