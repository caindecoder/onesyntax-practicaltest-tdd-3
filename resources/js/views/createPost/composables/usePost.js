import { ref } from 'vue';

export function usePost() {
    const posts = ref([]);
    const websites = ref([]);
    const message = ref('');
    const messageType = ref(''); // 'success' or 'error'

    const fetchPosts = async () => {
        try {
            const response = await fetch('/api/posts');
            posts.value = await response.json();
        } catch (error) {
            message.value = 'Error fetching posts.';
            messageType.value = 'error';
        }
    };

    const fetchWebsites = async () => {
        try {
            const response = await fetch('/api/websites');
            websites.value = await response.json();
        } catch (error) {
            message.value = 'Error fetching websites.';
            messageType.value = 'error';
        }
    };

    const createPost = async (postData) => {
        try {
            const response = await fetch('/api/posts', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(postData),
            });

            if (!response.ok) {
                const data = await response.json();
                throw new Error(data.error || 'Failed to create post.');
            }

            const newPost = await response.json();
            posts.value.push(newPost);
            message.value = 'Post created successfully.';
            messageType.value = 'success';
        } catch (error) {
            message.value = error.message || 'Error creating post.';
            messageType.value = 'error';
        }
    };

    return {
        posts,
        websites,
        message,
        messageType,
        fetchPosts,
        fetchWebsites,
        createPost,
    };
}
