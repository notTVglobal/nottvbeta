import { defineStore } from "pinia";

const initialState = () => ({
    showVolumeIndicator: true,
})

export const useOsdStore = defineStore('osdStore', {
    state: initialState,
    actions: {
        reset() {
            // Reset the store to its original state (clear all data)
            Object.assign(this, initialState());
        },
    },

})
