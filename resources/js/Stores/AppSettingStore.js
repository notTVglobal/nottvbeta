import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useUserStore } from "@/Stores/UserStore"
import { defineStore } from 'pinia'
import { Inertia } from '@inertiajs/inertia'

const initialState = () => ({
    loggedIn: false, // moved from userStore to here.
    pageReload: false, // if set to true the page will reload when we land on the Welcome page.
    noLayout: false, // when true it enables a special "no layout" page class
    thisUrl: window.location.pathname,
    prevUrl: null,
    showNavDropdown: false, // formerly userStore.showNavDropdown
    showFlashMessage: true, // formerly appSettingStore.showFlashMessage
    showConfirmationDialog: false, // show confirmation Dialog
    currentPage: '', // formerly videoPlayerStore.currentPage
    fullPage: false, // Used to determine layout FullPage or TopRight
    pageIsHidden: true, // Used to hide the page when fullPage = false && showOtt = true
    shouldScrollToTop: false,
    ott: 0, // Number representing the Ott Panel currently open. 0 is closed.
            // 1: Info
            // 2: Channels
            // 3: Playlist
            // 4: Chat
            // 5: Filters
    showOtt: false, // Over-The-Top (OTT) Panel, show (true) or hide (false)
    showOttButtons: true, // formerly videoPlayerStore.ottButtons
    osd: false, // On Screen Display (OSD) to be expanded into 8 regions
    osdSlot: {
        a: true, // On Screen Display A (Top Left)
        b: true, // On Screen Display B (Top Right)
        c: true, // On Screen Display C (Bottom Left)
        d: true, // On Screen Display D (Bottom Right)
    },
    pipChatMode: false, // Chat input focused uses pipChatMode when userStore.isMobile
    pageBgColor: 'bg-gray-800', // Active background color
    primaryBgColor: 'bg-gray-800', // Primary background color
    noLayoutBgColor: 'bg-gray-900', // Primary background color
    pipBgColor: 'bg-black', // Background color for pipChatMode
    // Other global UI settings...
    chatMessageBgColor: 'bg-gray-600', // Active chat message background color*
    primaryChatMessageBgColor: 'bg-gray-600', // Primary chat message background color
    pipChatMessageBgColor: 'bg-gray-900', // Chat message background color for PiP Chat Mode
})

export const useAppSettingStore = defineStore('appSettingStore', {
    state: initialState,
    actions: {
        reset() {
            // Reset the store to its original state (clear all data)
            Object.assign(this, initialState())
        },
        turnPipChatModeOn() {
            const videoPlayerStore = useVideoPlayerStore()
            const userStore = useUserStore()

            if(userStore.isMobile) {
                if(this.fullPage) {
                    videoPlayerStore.class = 'pipVideoClassFullPage'
                    videoPlayerStore.videoContainerClass = 'pipVideoContainerFullPage'
                } else {
                    videoPlayerStore.class = 'pipVideoClassTopRight'
                    videoPlayerStore.videoContainerClass = 'pipVideoContainerTopRight'
                    this.showOttButtons = false
                }
                this.pipChatMode = true
                this.hidePage = true
                this.pageBgColor = this.pipBgColor
                this.chatMessageBgColor = this.pipChatMessageBgColor
            }
        },
        turnPipChatModeOff() {
            const videoPlayerStore = useVideoPlayerStore()
            const userStore = useUserStore()

            if(userStore.isMobile) {
                if (this.fullPage) {
                    videoPlayerStore.videoContainerClass = 'fullPageVideoContainer'
                    videoPlayerStore.class = 'fullPageVideoClass'
                } else {
                    videoPlayerStore.videoContainerClass = 'topRightVideoContainer'
                    videoPlayerStore.class = 'topRightVideoClass'
                    this.showOttButtons = true
                }
                this.pipChatMode = false
                this.hidePage = false
                this.pageBgColor = this.primaryBgColor
                this.chatMessageBgColor = this.primaryChatMessageBgColor
            }
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
            this.showOttButtons = true
            this.showOtt = false // tec21: I don't know why we would want this... it could be a leftover.
        },
        toggleOsd() {
            const videoPlayerStore = useVideoPlayerStore()
            this.osd = !this.osd;
            videoPlayerStore.controls = !videoPlayerStore.controls
            if (this.ott && !this.osd) {
                this.showOttButtons = false
            } else if (this.ott && this.osd) {
                this.showOttButtons = false
            } else if (!this.ott && !this.osd) {
                this.showOttButtons = false
            } else if (!this.ott && this.osd) {
                this.showOttButtons = true
            }


        },
        // toggleOtt(num) {
        //     const userStore = useUserStore()
        //     if (this.ott === num) {
        //         if (this.fullPage) {
        //             this.toggleOtt(0)
        //             if (userStore.isMobile) {
        //                 this.osd = true
        //             }
        //         } else {
        //             // change this to toggleOtt(1)
        //             // as part of the go back to ottInfo function
        //             this.toggleOtt(0)
        //         }
        //         this.showOttButtons = true
        //     } else {
        //         this.ott = num
        //         if (this.fullPage) {
        //             this.showOttButtons = false
        //             if (userStore.isMobile) {
        //                 this.osd = false
        //             }
        //         }
        //     }
        // },


        toggleOtt(num) {
            const userStore = useUserStore();
            this.ott = num === this.ott && !this.fullPage ? 1 : num;

            // Handling the visibility of ottSlot.one and ottSlot.two
            this.osdSlot.a = this.fullPage && this.ott === 0;
            this.osdSlot.b = this.fullPage && this.ott === 0;

            if (this.fullPage) {
                this.showOttButtons = this.ott === 0;
                if (userStore.isMobile) {
                    this.osd = this.ott === 0;
                }
            } else {
                // Assuming default behavior for non-fullPage state is required here,
                // adjust as necessary based on additional context.
                this.showOttButtons = true;
                if (userStore.isMobile) {
                    this.osd = this.ott !== 0;
                }
            }
        },




        toggleOttInfo() {
            // Simplifying the logic based on the updated toggleOtt behavior
            this.toggleOtt(1);
        },

        toggleOttChannels() {
            this.toggleOtt(2);
        },

        toggleOttPlaylist() {
            this.toggleOtt(3);
        },

        toggleOttChat() {
            this.toggleOtt(4);
        },

        toggleOttFilters() {
            this.toggleOtt(5);
        },

        closeOtt() {
            // Simplified close logic based on the updated toggleOtt method
            this.toggleOtt(0);
        },







        // toggleOttInfo() {
        //     if (this.ott === 1) {
        //         // comment this out as part of the go back to ottInfo function
        //         this.toggleOtt(1)
        //     } else {
        //         this.toggleOtt(1)
        //     }
        // },
        // toggleOttChannels() {
        //     this.toggleOtt(2)
        // },
        // toggleOttPlaylist() {
        //     this.toggleOtt(3)
        // },
        // toggleOttChat() {
        //     this.toggleOtt(4)
        // },
        // toggleOttFilters() {
        //     this.toggleOtt(5)
        // },
        // closeOtt() {
        //     this.toggleOtt(0)
        //     // uncomment this as part of the go back to ottInfo function
        //     // if (appSettingStore.fullPage) {
        //     //     this.toggleOtt(0)
        //     // } else {
        //     //     this.toggleOtt(1)
        //     // }
        //
        // },
        setPrevUrl() {
            const userStore = useUserStore()
            const currentUrl = window.history.state ? window.history.state.url : window.location.pathname
            const currentQueryString = window.location.search;
            // If thisUrl has not been set yet, it means the user accessed the page directly
            if (!this.thisUrl) {
                this.prevUrl = userStore.isCreator ? '/dashboard' : '/';
            }
            // Update prevUrl only if navigating to a new page and there's no query string
            else if (this.thisUrl !== currentUrl && currentQueryString === '') {
                this.prevUrl = this.thisUrl;
            }
            // Update thisUrl to the current page
            this.thisUrl = currentUrl;
        },
        btnRedirect(newUrl) {
            this.setPrevUrl()
            Inertia.visit(newUrl)
        },
        back() {
            const videoPlayerStore = useVideoPlayerStore()
            const prevUrl = this.prevUrl
            this.setPrevUrl()
            videoPlayerStore.makeVideoTopRight()
            this.pageIsHidden = false
            Inertia.visit(prevUrl)
        },
    }
});
