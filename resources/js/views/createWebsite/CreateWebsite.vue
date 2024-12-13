<script>
import { WebsiteRequest } from './composables/websiteRequest.js';
import { WebsiteInteractor } from './composables/websiteInteractor.js';
import CreateWebsiteForm from './components/CreateWebsiteForm.vue';
import WebsiteList from './components/WebsiteList.vue';
import Notification from './components/Notification.vue';

export default {
    components: {
        CreateWebsiteForm,
        WebsiteList,
        Notification,
    },
    data() {
        return {
            websites: [],
            message: '',
            messageType: '',
        };
    },
    async created() {
        this.interactor = new WebsiteInteractor();
        try {
            this.websites = await this.interactor.fetchWebsites();
        } catch (error) {
            this.message = error.message;
            this.messageType = 'error';
        }
    },
    methods: {
        async handleWebsiteCreated(websiteData) {
            try {
                const websiteRequest = new WebsiteRequest(websiteData);
                const newWebsite = await this.interactor.createWebsite(websiteRequest);
                this.websites.push(newWebsite);
                this.message = 'Website created successfully.';
                this.messageType = 'success';
            } catch (error) {
                this.message = error.message;
                this.messageType = 'error';
            }
        },
    },
};
</script>

<template>
    <div class="create-website">
        <h1>Create a Website</h1>
        <Notification :message="message" :type="messageType" />
        <CreateWebsiteForm @websiteCreated="handleWebsiteCreated" />
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
