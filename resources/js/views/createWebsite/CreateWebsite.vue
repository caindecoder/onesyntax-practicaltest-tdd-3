<script>
import WebsiteInteractor from './composables/websiteInteractor.js';
import CreateWebsiteForm from './components/CreateWebsiteForm.vue';
import WebsiteList from './components/WebsiteList.vue';
import Notification from '../shared/Notification.vue';

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
        await this.loadWebsites();
    },
    methods: {
        async loadWebsites() {
            try {
                this.websites = await this.interactor.fetchWebsites();
            } catch (error) {
                this.message = error.message;
                this.messageType = 'error';
            }
        },
        async handleWebsiteCreated(websiteData) {
            try {
                const newWebsite = await this.interactor.createWebsite(websiteData);
                this.websites.push(newWebsite);
                this.message = 'Website created successfully!';
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
