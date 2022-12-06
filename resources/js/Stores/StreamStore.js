import { defineStore } from "pinia";
import { useChatStore } from "@/Stores/ChatStore";

export let useStreamStore = defineStore('streamStore', {
    state: () => ({
        isLive: Boolean,
        currentChannel: String,
        name: '',
        description: '',
        posterName: [],
        posterId: [0],
        posterUrl: '',
        episodes: [],
        team_id: 'team id',
        teamName: '',
        showOSD: Boolean,
        showChannels: Boolean,
    }),

    actions: {
        toggleChat() {
            this.showOSD = !this.showOSD;
            useChatStore().toggleChat();
        },
        toggleChannels() {
            this.showOSD = !this.showOSD;
            this.showChannels = !this.showChannels;
        }
    },

    getters: {
        //
    }
});
