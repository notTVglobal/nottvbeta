import { defineStore } from 'pinia';
import videojs from "video.js";

export const useAppSettingStore = defineStore('appSetting', {
    state: () => ({
        pipChatMode: false,
        hidePage: false,
        pageBgColor: 'bg-gray-800', // Active background color
        primaryBgColor: 'bg-gray-800', // Primary  background color
        pipBgColor: 'bg-black', // Background color for PiP Chat Mode
        // Other global UI settings...
        chatMessageBgColor: 'bg-gray-600', // Active chat message background color
        primaryChatMessageBgColor: 'bg-gray-600', // Primary chat message background color
        pipChatMessageBgColor: 'bg-gray-900', // Chat message background color for PiP Chat Mode
    }),
    actions: {
        togglePipChatMode() {
            this.pipChatMode = !this.pipChatMode;

            // Update pageBgColor based on the new state of pipChatMode
            if (this.pipChatMode) {
                // If pipChatMode is now true
                this.hidePage = true;
                this.setPageBgColor(this.pipBgColor);
                this.setChatMessageBgColor(this.pipChatMessageBgColor);
                // let videoJs = videojs('main-player')
                // videoJs.controls(false)
            } else {
                // If pipChatMode is now false
                this.hidePage = false;
                this.setPageBgColor(this.primaryBgColor);
                this.setChatMessageBgColor(this.primaryChatMessageBgColor);
            }
        },
        setPageBgColor(color) {
            this.pageBgColor = color;
        },
        // Other actions to modify UI settings...
        setChatMessageBgColor(color) {
            this.chatMessageBgColor = color;
        },
    }
});
