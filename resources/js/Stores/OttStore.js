import { useAppSettingStore } from "@/Stores/AppSettingStore"
import { defineStore } from "pinia";

const initialState = () => ({
    showOttContent: false,
})

export const useOttStore = defineStore('ottStore', {
    state: initialState,
    actions: {
        reset() {
            // Reset the store to its original state (clear all data)
            Object.assign(this, initialState());
        },
        showContentForDemo() {
            const appSettingStore = useAppSettingStore()
            if (appSettingStore.fullPage && appSettingStore.loggedIn) {
                setTimeout(() => {
                    this.showOttContent = true
                    this.hideContentForDemo()
                }, 1000);
            }
        },
        hideContentForDemo(){
            setTimeout(() => {
                this.showOttContent = false
            }, 10000);
        },
        hideOttContent() {
            this.showOttContent = false
        }
    },

})
