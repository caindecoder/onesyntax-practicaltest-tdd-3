import { ref } from 'vue';

export function useWebsite() {
    const websites = ref([]);
    const message = ref('');
    const messageType = ref(''); // 'success' or 'error'

    const fetchWebsites = async () => {
        try {
            const response = await fetch('/api/websites');
            websites.value = await response.json();
        } catch (error) {
            message.value = 'Error fetching websites.';
            messageType.value = 'error';
        }
    };

    const createWebsite = async (websiteData) => {
        try {
            const response = await fetch('/api/websites', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(websiteData),
            });

            if (!response.ok) {
                const data = await response.json();
                throw new Error(data.error || 'Failed to create website.');
            }

            const newWebsite = await response.json();
            websites.value.push(newWebsite);
            message.value = 'Website created successfully.';
            messageType.value = 'success';
        } catch (error) {
            message.value = error.message || 'Error creating website.';
            messageType.value = 'error';
        }
    };

    return {
        websites,
        message,
        messageType,
        fetchWebsites,
        createWebsite,
    };
}
