import { ref } from 'vue';

export function useSubscription() {
    const subscriptions = ref([]);
    const websites = ref([]);
    const message = ref('');
    const messageType = ref(''); // 'success' or 'error'

    const fetchSubscriptions = async () => {
        try {
            const response = await fetch('/api/subscriptions');
            subscriptions.value = await response.json();
        } catch (error) {
            message.value = 'Error fetching subscriptions.';
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

    const createSubscription = async (subscriptionData) => {
        try {
            const response = await fetch('/api/subscriptions', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(subscriptionData),
            });

            if (!response.ok) {
                const data = await response.json();
                throw new Error(data.error || 'Failed to create subscription.');
            }

            const newSubscription = await response.json();
            subscriptions.value.push(newSubscription);
            message.value = 'Subscription created successfully.';
            messageType.value = 'success';
        } catch (error) {
            message.value = error.message || 'Error creating subscription.';
            messageType.value = 'error';
        }
    };

    return {
        subscriptions,
        websites,
        message,
        messageType,
        fetchSubscriptions,
        fetchWebsites,
        createSubscription,
    };
}
