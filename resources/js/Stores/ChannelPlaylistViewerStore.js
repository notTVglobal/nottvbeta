import { defineStore } from "pinia";

const initialState = () => ({
    //
})

export const useChannelPlaylistViewerStore = defineStore('channelPlaylistViewerStore', {
    state: initialState,
    actions: {
        reset() {
            // Reset the store to its original state (clear all data)
            Object.assign(this, initialState());
        },
        //
    },

    getters: {
        //
    }
});
