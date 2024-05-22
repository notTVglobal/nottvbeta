import { defineStore } from 'pinia'
import { useUserStore } from '@/Stores/UserStore'
import { useNotificationStore } from '@/Stores/NotificationStore'
import dayjs from 'dayjs'
import timezone from 'dayjs/plugin/timezone'
import utc from 'dayjs/plugin/utc'

dayjs.extend(utc)
dayjs.extend(timezone)

const initialState = () => ({
    id: null,
    name: '',
    slug: '',
    url: '',
    show_status_id: 0,
    episode_play_order: '',
    scheduleDetails: {},
    episodeName: '',
    episodeUrl: '',
    category_id: 0,
    category_description: '',
    categories: [],
    sub_category_id: 0,
    sub_category_description: '',
    sub_categories: [],
    description: '',
    posterName: [],
    posterId: [0],
    episodes: [],
    team_id: 'team id',
    episodePoster: '',
    noteEdit: 0,
    notes: '',
    saveNoteProcessing: Boolean,
    errorMessage: '',
    episodeIsBeingDeleted: 0, // put episode id here if being deleted (used on the Show Manage page, Show Episode component)
    openComponent: 'showEpisodes',
    isLive: false,
    liveScheduledStartTime: '',
    liveMistStreamName: '',
    isScheduled: null,
    isUpdatingSchedule: false,
    updatedBy: null,
    isSaving: null,
    loadingUpdatingStatus: false, // to show a loader
    leader: null, // for handling functions on the Echo broadcast channel
})

export const useShowStore = defineStore('showStore', {
    state: initialState,
    actions: {
        reset() {
            // Reset the store to its original state (clear all data)
            Object.assign(this, initialState())
        },
        initializeShow(show) {
            // console.log('initializeShow')

            let meta = {};
            try {
                if (show.meta) {
                    meta = typeof show.meta === 'string' ? JSON.parse(show.meta) : show.meta;
                }
            } catch (error) {
                console.error('Error parsing meta:', error);
            }

            this.$patch({
                id: show.id,
                name: show.name,
                slug: show.slug,
                scheduleDetails: show.scheduleDetails,
                isScheduled: meta.isScheduled ?? false,
                isSaving: meta.isSaving ?? false,
                isUpdatingSchedule: meta.isUpdatingSchedule ?? null,
                updatedBy: meta.updatedBy ?? null,
            });

        },
        setLeader(user) {
            this.leader = user;
        },
        resetLeader() {
            this.leader = null;
            // Reset other states...
        },
        async checkIsLive(showSlug) {
            try {
                const response = await axios.get(`/api/shows/${showSlug}/check-live`)
                this.isLive = response.data.isLive
                this.liveScheduledStartTime = response.data.liveScheduledStartTime
                this.liveMistStreamName = response.data.mistStreamName
            } catch (error) {
                console.error('Error checking if show is live:', error)
            }
        },
        // async fill() {
        //     let r = await import('@/Json/show.json')
        //     this.$state = r.default
        // },
        setName(name) {
            this.name = name
        },
        setUrl(url) {
            this.url = url
        },
        setEpisodeName(episodeName) {
            this.episodeName = episodeName
        },
        setEpisodeUrl(episodeUrl) {
            this.episodeUrl = episodeUrl
        },
        initializeDescriptions(categoryId, subCategoryId) {
            this.category_id = categoryId
            const category = this.categories.find(cat => cat.id === categoryId)
            this.category_description = category ? category.description : ''
            this.sub_categories = category ? category.sub_categories : []

            this.updateSubCategoryDescription(subCategoryId)
        },
        updateSubCategoryDescription(subCategoryId) {
            // Ensure sub_categories is not undefined
            if (!this.sub_categories) {
                this.sub_category_id = ''
                this.sub_category_description = ''
                return
            }

            const subCategory = this.sub_categories.find(sub => sub.id === subCategoryId)
            this.sub_category_id = subCategory ? subCategory.id : ''
            this.sub_category_description = subCategory ? subCategory.description : ''
        },
        async addShowToSchedule(payload) {
            this.loadingUpdatingStatus = true
            try {
                // Append contentType and contentId to the payload
                payload.contentType = 'show'; // Adjust this if necessary
                payload.contentId = this.id;

                const response = await axios.post('/api/schedule/addToSchedule', payload)
                this.loadingUpdatingStatus = false
                this.isScheduled = true
                return response.data
            } catch (error) {
                this.loadingUpdatingStatus = false
                throw error
            }
        },
        async setUpdatingStatus(state, userName, slug) {
            const notificationStore = useNotificationStore();
            this.loadingUpdatingStatus = true
            try {
                // Set the local saving state
                this.isUpdatingSchedule = state;
                this.updatedBy = state ? userName : null;
                // console.log(userName);
                // Update the meta field in the database
                const response = await axios.put(`/api/shows/${slug}/meta`, {
                    // isSaving: null,
                    isUpdatingSchedule: state,
                    updatedBy: userName,
                })
                this.loadingUpdatingStatus = false
                // Check for the notification type and handle it accordingly
                if (response.data.notificationType !== 'silent') {
                    notificationStore.setToastNotification(response.data.message, 'info');
                }
            } catch (error) {
                console.error('Error updating saving state:', error)
                this.loadingUpdatingStatus = false
                notificationStore.setToastNotification(error.message, 'error')
                throw error
            }
        },
        preparePayload(form) {
            const userStore = useUserStore()
            let startDate, endDate, formattedDuration
            // console.log(form)
            if (form.scheduleType === 'one-time') {
                // One-time scheduling logic
                startDate = dayjs(form.startDate).tz(userStore.canadianTimezone, true)
                form.startDate = startDate.format()

                let durationHours = Number(form.durationHour)
                let durationMinutes = Number(form.durationMinute)
                form.duration = (durationHours * 60) + durationMinutes

                endDate = startDate.add(durationHours, 'hour').add(durationMinutes, 'minute')
                form.endDate = endDate.tz(userStore.canadianTimezone, true).format()

                form.startTime = null
                form.daysOfWeek = null
            } else if (form.scheduleType === 'recurring') {
                // Recurring scheduling logic
                let hour = parseInt(form.startTime.hour) % 12
                if (form.startTime.meridian === 'PM') hour += 12
                startDate = dayjs(form.startDate).hour(hour).minute(form.startTime.minute)
                form.startDate = startDate.tz(userStore.canadianTimezone, true).format()

                let newEndTime = dayjs(form.startDate).add(form.durationHour, 'hours').add(form.durationMinute, 'minutes')
                form.endTime = newEndTime.format('HH:mm:ss')

                let endDateOnly = dayjs(form.endDate).format('YYYY-MM-DD')
                form.endDate = dayjs(endDateOnly + ' ' + form.endTime).format('YYYY-MM-DD HH:mm:ss')
                form.endDate = dayjs(form.endDate).tz(userStore.canadianTimezone, true).format()

                form.startTime = startDate.format('HH:mm:ss')
                formattedDuration = (parseInt(form.durationHour) * 60) + parseInt(form.durationMinute)
                form.duration = formattedDuration
            }

            return {
                contentType: 'show',
                contentId: form.contentId,
                scheduleType: form.scheduleType,
                startTime: form.startTime,
                duration: form.duration,
                startDate: form.startDate,
                endDate: form.endDate,
                daysOfWeek: form.scheduleType === 'recurring' ? form.daysOfWeek : [],
                timezone: userStore.canadianTimezone,
            }
        },
        async removeFromSchedule(contentType, contentId) {
            this.loadingUpdatingStatus = true
            const notificationStore = useNotificationStore()
            try {
                const payload = {
                    data: {
                        contentType,
                        contentId,
                    },
                }
                // console.log(payload)
                await axios.delete('/api/schedule/removeFromSchedule', payload)
                this.loadingUpdatingStatus = false
                this.isScheduled = false
                // Optionally, update the state here if needed
            } catch (error) {
                notificationStore.setToastNotification('There was an error.', 'error')
                console.error('There was an error removing the item from the schedule:', error)
                this.loadingUpdatingStatus = false
                throw error
            }
        },
        // initializeEchoListener(showId) {
        //     const showChannel = `show.${showId}`
        //     Echo.channel(showChannel)
        //         .listen('ScheduleUpdated', (event) => {
        //             if (event.showId === showId) {
        //                 this.setUpdatingStatus(showId, false)
        //             }
        //         })
        // },
        // leaveEchoListener(showId) {
        //     const showChannel = `show.${showId}`
        //     Echo.leaveChannel(showChannel)
        // },

    },

    getters: {
        //
    },

})
