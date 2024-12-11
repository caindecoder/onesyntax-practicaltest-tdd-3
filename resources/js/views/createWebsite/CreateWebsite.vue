<script>
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
            messageType: '', // 'success' or 'error'
        };
    },
    mounted() {
        this.fetchWebsites();
    },
    methods: {
        async fetchWebsites() {
            try {
                const response = await fetch('/api/websites');
                const data = await response.json();
                this.websites = data;
            } catch (error) {
                this.message = 'Error fetching websites.';
                this.messageType = 'error';
            }
        },
        handleWebsiteCreated(newWebsite) {
            this.websites.push(newWebsite);
            this.message = 'Website created successfully.';
            this.messageType = 'success';
        },
    },
};
</script>

<template>
    <div class="create-website">
        <h1>Create a New Website</h1>
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
