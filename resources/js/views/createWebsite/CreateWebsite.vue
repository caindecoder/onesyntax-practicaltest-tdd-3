<script>
import { useWebsite } from './composables/useWebsite.js';
import CreateWebsiteForm from './components/CreateWebsiteForm.vue';
import WebsiteList from './components/WebsiteList.vue';
import Notification from './components/Notification.vue';

export default {
    components: {
        CreateWebsiteForm,
        WebsiteList,
        Notification,
    },
    setup() {
        const { websites, message, messageType, fetchWebsites, createWebsite } = useWebsite();

        fetchWebsites();

        const handleWebsiteCreated = (websiteData) => {
            createWebsite(websiteData);
        };

        return {
            websites,
            message,
            messageType,
            handleWebsiteCreated,
        };
    },
};
</script>

<template>
    <div class="create-website">
        <h1>Create a Website</h1>
        <Notification :message="message" :type="messageType" v-if="message" />

        <CreateWebsiteForm @websiteCreated="handleWebsiteCreated" />

        <h2>Existing Websites</h2>
        <WebsiteList :websites="websites" />
    </div>
</template>

<style scoped>
.create-website {
    max-width: 800px;
    margin: 0 auto;
    text-align: center;
}
</style>
