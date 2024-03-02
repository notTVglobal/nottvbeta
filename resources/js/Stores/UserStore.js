import { defineStore } from 'pinia'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
import { Inertia } from '@inertiajs/inertia'
import { ref } from 'vue'
import dayjs from 'dayjs'
import utc from 'dayjs/plugin/utc'
import timezone from 'dayjs/plugin/timezone'

// Extend Day.js with the necessary plugins
dayjs.extend(utc)
dayjs.extend(timezone)

// const appSettingStore = useAppSettingStore()

const initialState = () => ({
    isMobile: Boolean,
    showNavDropdown: Boolean,

    // move currentPage from VideoPlayerStore to here.
    currentPage: String,
    hidePage: Boolean,
    thisUrl: window.location.pathname,
    loggedIn: false,
    prevUrl: null,
    id: null,
    roleId: null,
    getUserDataCompleted: false,
    hasAccount: null,
    isAdmin: false,
    isCreator: false,
    isNewsPerson: false,
    isVip: false,
    isSubscriber: false,
    oldLoggedOutId: null,
    uploadPercentage: 0,
    scrollToTopCounter: 0,
    uploadMovieId: null,
    uploadShowEpisodeId: null,
    upgradeSelection: '',
    testNum: 0,
    url: null,
    key: 0,
    reloadNav: 0,
    showFlashMessage: false,
    newNotifications: 0,
    showNotifications: false,
    notifications: ref([]),
    notificationsKey: 0,
    userSubscribedToNotifications: false,
    timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
    timezones: [
        'America/Vancouver', // PT
        'America/Edmonton',  // MT
        'America/Winnipeg',  // CT
        'America/Toronto',   // ET
        'America/Halifax',   // AT
        'America/St_Johns',   // NT
    ],
})

export const useUserStore = defineStore('userStore', {
    state: initialState,
    actions: {
        reset() {
            // Reset the store to its original state (clear all data)
            Object.assign(this, initialState())
        },
        async fetchUserData() {
            try {
                const response = await axios.post('/getUserStoreData');
                this.id = response.data.id;
                this.loggedIn = true
                this.isAdmin = response.data.isAdmin
                this.isCreator = response.data.isCreator
                this.isNewsPerson = response.data.isNewsPerson
                this.isVip = response.data.isVip
                this.isSubscriber = response.data.isSubscriber
                this.hasAccount = response.data.hasAccount
                this.getUserDataCompleted = true
                console.log('get user data on AppLayout')
                await this.subscribeToUserNotifications(response.data.id)
                await this.updateUserTimezone;
                if (this.isCreator) {
                    this.prevUrl = '/dashboard';
                } else {
                    this.prevUrl = '/stream';
                }
            } catch (error) {
                console.error('Failed to fetch user data:', error);
            }
        },
        async updateUserTimezone() {
            try {
                const response = await axios.post('/users/update-timezone', {timezone: this.timezone})
                console.log(response.data.message)
            } catch (error) {
                console.error(error.response ? error.response.data : error)
            }
        },
        toggleNavDropdown() {
            this.showNavDropdown = !this.showNavDropdown
            // appSettingStore.osd = true
        },
        closeNavDropdown() {
            this.showNavDropdown = false
            // useVideoPlayerStore().closeOtt()
        },
        checkIsMobile() {
            let screenWidth = screen.width
            this.isMobile = screenWidth <= 926
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
            this.notifications = this.notifications.filter(notification => notification.id !== id)
            // this.newNotifications--;
        },
        async subscribeToUserNotifications(userId) {
            await this.fetchNotifications()
            Echo.private(`user.${userId}`).subscribed(() => {
            }).listen('.userNotifications', (event) => {
                this.newNotifications++
                this.notifications.push(event.notification)
            })
            this.userSubscribedToNotifications = true
        },
        async fetchNotifications() {
            const response = await fetch(`/notifications`)
            const data = await response.json()
            if (data.notifications && Array.isArray(data.notifications)) {
                this.notifications = data.notifications
                this.newNotifications = data.notifications.length
            } else {
                // Handle the case where notifications are missing or not an array
                this.newNotifications = 0 // or some other default value or error handling logic
            }
        },
        convertUtcToUserTimezone(utcDate) {
            // Ensure the date is treated as UTC, then convert to the desired timezone
            const dateInUserTimezone = dayjs.utc(utcDate).tz(this.timezone)
            return dateInUserTimezone.format() // You can adjust the format string as needed
        },
        // Optional: a method to format the datetime string in a specific way
        formatDateTimeInUserTimezone(date, formatString = 'YYYY-MM-DD HH:mm:ss') {
            return dayjs.tz(date, this.timezone).format(formatString)
        },
        formatDateTimeFromUtcToUserTimezone(dateTime, formatString = 'ddd DD MMM  h:mm a') {
            if (!this.timezone) {
                console.error('Timezone is not set.')
                return dateTime // Or handle this case as appropriate for your app
            }
            return dayjs.utc(dateTime).tz(this.timezone).format(formatString)
        },
        formatTimeInUserTimezone(time, formatString = 'h:mm a') {
            if (!this.timezone) {
                console.error('Timezone is not set.')
                return time // Or handle this case as appropriate for your app
            }
            // Assuming the incoming time is like '21:00:00' and you want to convert it
            const dateTime = `2000-01-01T${time}Z` // Prepend a placeholder date
            // const formattedTime = dayjs.utc(dateTime).tz(this.timezone);
            return dayjs.utc(dateTime).tz(this.timezone).format(formatString)
        },
        formatDateInUserTimezone(dateTime, formatString = 'ddd DD MMM') {
            if (!this.timezone) {
                console.error('Timezone is not set.')
                return dateTime // Or handle this case as appropriate for your app
            }
            return dayjs.utc(dateTime).tz(this.timezone).format(formatString)
        },
        async setUserTimezone(newTimezone) {
            this.timezone = newTimezone
            await axios.post('/users/update-timezone', {'timezone': this.timezone})
        },
    },

    getters: {
        uploadPercentageRounded(state) {
            if (this.uploadPercentage !== 0) {
                return Math.round(state.uploadPercentage * 10) / 10
            }
        },
        canadianTimezone: (state) => {
            const timezoneMapping = {
                'America/Los_Angeles': 'America/Vancouver',
                'America/Tijuana': 'America/Vancouver',
                'America/Denver': 'America/Edmonton',
                'America/Phoenix': 'America/Edmonton',
                'America/Chicago': 'America/Winnipeg',
                'America/Mexico_City': 'America/Winnipeg',
                'America/New_York': 'America/Toronto',
                'America/Detroit': 'America/Toronto',
                'America/Kentucky/Louisville': 'America/Toronto',
                'America/Indiana/Indianapolis': 'America/Toronto',
                'America/Atlantic': 'America/Halifax',
                'America/Caracas': 'America/Halifax',
                'America/St_Johns': 'America/St_Johns',
            }

            return timezoneMapping[state.timezone] || state.timezone
        },
        timezoneAbbreviation(state) {
            const mapping = {
                'America/Vancouver': 'PT',
                'America/Edmonton': 'MT',
                'America/Winnipeg': 'CT',
                'America/Toronto': 'ET',
                'America/Halifax': 'AT',
                'America/St_Johns': 'NT',
            }
            return mapping[state.canadianTimezone] || 'Unknown'
        },
        canadianTimezoneDescription(state) {
            const descriptionMapping = {
                'America/Vancouver': 'Pacific',
                'America/Edmonton': 'West',
                'America/Winnipeg': 'Central',
                'America/Toronto': 'Ontario',
                'America/Ottawa': 'Ontario', // Assuming you want to explicitly mention Ottawa for Ontario
                'America/Montreal': 'Quebec', // Assuming Montreal represents the Quebec timezone
                'America/Halifax': 'East',
                'America/St_Johns': 'Atlantic',
                // Add other mappings as needed
            }
            return descriptionMapping[state.canadianTimezone] || 'Unknown'
        },
    },
})
