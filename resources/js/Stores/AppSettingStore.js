import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useUserStore } from "@/Stores/UserStore"

import { defineStore } from 'pinia'

const initialState = () => ({
    showNavDropdown: false, // formerly userStore.showNavDropdown
    currentPage: '', // formerly videoPlayerStore.currentPage
    fullPage: false, // Used to determine layout FullPage or TopRight
    pageIsHidden: true, // Used to hide the page when fullPage = false && showOtt = true
    ott: 0, // Number representing the Ott Panel currently open. 0 is closed.
    showOtt: false, // Over-The-Top (OTT) Panel, show (true) or hide (false)
    showOttButtons: true, // formerly videoPlayerStore.ottButtons
    osd: false, // On Screen Display (OSD) to be expanded into 8 regions. See below for an example. FEATURE TO BE DEVELOPED. osd is formerly videoPlayerStore.osd
    showOsd1: false, // On Screen Display 1 (Top Left)
    showOsd2: false, // On Screen Display 2 (Top Right)
    showOsd3: false, // On Screen Display 3 (Bottom Left)
    showOsd4: false, // On Screen Display 4 (Bottom Right)
    pipChatMode: false, // Chat input focused uses pipChatMode when userStore.isMobile
    pageBgColor: 'bg-gray-800', // Active background color
    primaryBgColor: 'bg-gray-800', // Primary background color
    pipBgColor: 'bg-black', // Background color for pipChatMode
    // Other global UI settings...
    chatMessageBgColor: 'bg-gray-600', // Active chat message background color*
    primaryChatMessageBgColor: 'bg-gray-600', // Primary chat message background color
    pipChatMessageBgColor: 'bg-gray-900', // Chat message background color for PiP Chat Mode
})

export const useAppSettingStore = defineStore('appSetting', {
    state: initialState,
    actions: {
        reset() {
            // Reset the store to its original state (clear all data)
            Object.assign(this, initialState())
        },
        turnPipChatModeOn() {
            this.pipChatMode = true
            this.hidePage = true
            this.pageBgColor = this.pipBgColor
            this.chatMessageBgColor = this.pipChatMessageBgColor
        },
        turnPipChatModeOff() {
            this.pipChatMode = false
            this.hidePage = false
            this.pageBgColor = this.primaryBgColor
            this.chatMessageBgColor = this.primaryChatMessageBgColor
        },
        setPageBgColor(color) {
            this.pageBgColor = color
        },
        // Other actions to modify UI settings...
        setChatMessageBgColor(color) {
            this.chatMessageBgColor = color
        },
        toggleNavDropdown() {
            this.showNavDropdown = ! this.showNavDropdown
            this.osd = !this.osd
            this.showOtt = !this.showOtt
            this.showOttButtons = !this.showOttButtons
        },
        closeNavDropdown() {
            this.showNavDropdown = false
            // this.showOtt = false // tec21: I don't know why we would want this... it could be a leftover.
        },
        toggleOtt(num) {
            if (this.ott === num) {
                this.ott = 0
            } else {
                this.ott = num
            }
        },
        toggleOttInfo() {
            this.toggleOtt(1)
        },
        toggleOttChannels() {
            this.toggleOtt(2)
        },
        toggleOttPlaylist() {
            this.toggleOtt(3)
        },
        toggleOttChat() {
            this.toggleOtt(4)
        },
        toggleOttFilters() {
            this.toggleOtt(5)
        },
        closeOtt() {
            this.toggleOtt(0)
        },
    }
});
