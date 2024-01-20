import { defineStore } from "pinia"
import { useAppSettingStore } from "@/Stores/AppSettingStore"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { Inertia } from "@inertiajs/inertia"
import { ref } from "vue"

// const appSettingStore = useAppSettingStore()

const initialState = () => ({
    isMobile: Boolean,
    showNavDropdown: Boolean,

    // move currentPage from VideoPlayerStore to here.
    currentPage: String,
    hidePage: Boolean,
    thisUrl: window.location.pathname,
    prevUrl: null,
    id: null,
    roleId: null,
    getUserDataCompleted: null,
    hasAccount: null,
    isAdmin: null,
    isCreator: null,
    isNewsPerson: null,
    isVip: null,
    isSubscriber: null,
    oldLoggedOutId: null,
    uploadPercentage: 0,
    scrollToTopCounter: 0,
    uploadMovieId: null,
    uploadShowEpisodeId: null,
    upgradeSelection: '',
    testNum: 0,
    url: null,
    key: 0,
    showFlashMessage: false,
    newNotifications: 0,
    showNotifications: false,
    notifications: ref([]),
    notificationsKey: 0,
    userSubscribedToNotifications: false,
    timezone: null,
})

export const useUserStore = defineStore('userStore', {
    state: initialState,
    actions: {
        reset() {
            // Reset the store to its original state (clear all data)
            Object.assign(this, initialState())
        },
        updateStore() {
            this.testNum++;
        },
        subtractStore() {
            this.testNum--;
        },
        toggleNavDropdown() {
            this.showNavDropdown = ! this.showNavDropdown;
            // appSettingStore.osd = true
        },
        closeNavDropdown() {
            this.showNavDropdown = false;
            // useVideoPlayerStore().closeOtt()
        },
        checkIsMobile() {
            let screenWidth = screen.width
            this.isMobile = screenWidth <= 926;
        },
        // checkIsSubscriber() {
        //     this.isSubscriber = !!(this.roleId === 2 || this.roleId === 3 || this.isAdmin);
        // },
        // checkIsVip() {
        //     this.userisVip = !!(this.roleId === 3 || this.isAdmin);
        // },
        // checkIsCreator() {
        //     this.isCreator = !!(this.roleId === 4 || this.isAdmin);
        // },
        // checkIsAdmin() {
        //     this.isAdmin =  !!(this.isAdmin);
        // },
        clearUserData() {
            this.currentPage = ''
            this.id = null
            this.roleId = null
            this.getUserDataCompleted = null
            this.hasAccount = null
            this.isAdmin = null
            this.isCreator = null
            this.isNewsPerson = null
            this.isVip = null
            this.isSubscriber = null
            this.oldLoggedOutId = null
            this.uploadPercentage = 0
            this.scrollToTopCounter = 0
            this.uploadMovieId = null
            this.uploadShowEpisodeId = null
            this.upgradeSelection = ''
            this.testNum = 0
            this.url = null
            this.key = 0
        },
        removeNotificationById(id) {
            this.notifications = this.notifications.filter(notification => notification.id !== id);
            // this.newNotifications--;
        },
        async subscribeToUserNotifications(userId) {
            await this.fetchNotifications()
            Echo.private(`user.${userId}`).subscribed(() => {
            }).listen('.userNotifications', (event) => {
                this.newNotifications++;
                this.notifications.push(event.notification);
            })
            this.userSubscribedToNotifications = true;
        },
        async fetchNotifications() {
            const response = await fetch(`/notifications`);
            const data = await response.json();
            if (data.notifications && Array.isArray(data.notifications)) {
                this.notifications = data.notifications;
                this.newNotifications = data.notifications.length;
            } else {
                // Handle the case where notifications are missing or not an array
                this.newNotifications = 0; // or some other default value or error handling logic
            }
        },
    },

    getters: {
        uploadPercentageRounded(state) {
            if (this.uploadPercentage !== 0) {
                return Math.round(state.uploadPercentage * 10) / 10
            }
        },
    }
});
