import { defineStore } from 'pinia'
import dayjs from 'dayjs'
import timezone from 'dayjs/plugin/timezone'
import utc from 'dayjs/plugin/utc'
import isSameOrBefore from 'dayjs/plugin/isSameOrBefore'
import isSameOrAfter from 'dayjs/plugin/isSameOrAfter'
import { useNotificationStore } from '@/Stores/NotificationStore'
import { useUserStore } from '@/Stores/UserStore'
import { useAdminStore } from '@/Stores/AdminStore'
import { router } from '@inertiajs/vue3'

dayjs.extend(utc)
dayjs.extend(timezone)
dayjs.extend(isSameOrBefore)
dayjs.extend(isSameOrAfter)

const initialState = () => ({
    playlists: [],
    selectedPlaylist: null,
    scheduleItems: [],
    gaps: [],
    startTime: dayjs().format('YYYY-MM-DDTHH:mm'),
    endTime: dayjs().add(24, 'hour').format('YYYY-MM-DDTHH:mm'),
    contentItems: [],
    contentFetched: false,
    currentPage: 1,
    totalPages: 1,
    searchQuery: '',
    showModal: false,
    loading: false,
    loadingSchedules: false,
    processing: false,
    error: null,
    id: null,
    name: '',
    description: '',
    url: '',
    type: 'regular',
    priority: 1,
    repeat_mode: 'repeat_all',
    next_playlist_id: null,
})

export const useChannelPlaylistStore = defineStore('channelPlaylistStore', {
    state: initialState,
    actions: {
        reset() {
            // Reset the store to its original state (clear all data)
            Object.assign(this, initialState())
        },
        openAddContentModal() {
            this.showModal = true
        },
        closeAddContentModal() {
            this.showModal = false
        },
        async fetchPlaylists() {
            this.loading = true
            try {
                const response = await axios.get('/admin/channel-playlist/get-playlists')
                this.playlists = response.data.playlists
                const {message, status} = response.data
                const notificationStore = useNotificationStore()
                notificationStore.setToastNotification(message, status)
            } catch (error) {
                this.error = error.response ? error.response.data.message : error.message
                const notificationStore = useNotificationStore()
                notificationStore.setToastNotification(this.error, 'error')
            } finally {
                this.loading = false
            }
        },
        async createPlaylist(playlist) {
            if (!this.validateScheduleItems()) return false;

            this.loading = true
            try {
                const response = await axios.post('/channelPlaylists', {
                    ...playlist,
                    scheduleItems: this.getValidScheduleItems(),
                })
                this.playlists.push(response.data.playlist)
                this.showNotification(response.data)
                this.reset()
                return true
            } catch (error) {
                this.handleError(error)
                return false
            } finally {
                this.loading = false
            }
        },
        async updatePlaylist(playlist) {
            const adminStore = useAdminStore();
            playlist.scheduleItems = this.scheduleItems;

            if (!this.validateScheduleItems(playlist.scheduleItems)) {
                console.error('Invalid schedule items');
                return false;
            }

            this.loading = true;
            try {
                const response = await axios.put(`/channelPlaylists/${playlist.id}`, playlist);
                const index = this.playlists.findIndex(p => p.id === playlist.id);
                if (index !== -1) {
                    this.playlists[index] = response.data.playlist;
                }

                const adminIndex = adminStore.items.findIndex(item => item.id === playlist.id);
                if (adminIndex !== -1) {
                    adminStore.items[adminIndex] = {
                        ...response.data.playlist,
                        playlist_items: this.scheduleItems
                    };
                }
                this.showNotification(response.data);
                document.getElementById('updateChannelPlaylistModal').close();
                this.clearError()
                return true;
            } catch (error) {
                this.handleError(error);
                return false;
            } finally {
                this.loading = false;
            }
        },

        validateScheduleItems() {
            const notificationStore = useNotificationStore()

            // Check for conflicts
            if (this.scheduleItems.some(item => item.conflict)) {
                notificationStore.setGeneralServiceNotification('Conflict Detected', 'There are conflicts in the schedule items. Please resolve them before proceeding.')
                return false
            }

            // Check for gaps
            const items = this.scheduleItems.filter(item => !item.removed && item.type !== 'gap').sort((a, b) => dayjs(a.start_dateTime).isBefore(dayjs(b.start_dateTime)) ? -1 : 1)
            for (let i = 0; i < items.length - 1; i++) {
                if (dayjs(items[i].end_dateTime).isBefore(dayjs(items[i + 1].start_dateTime))) {
                    notificationStore.setGeneralServiceNotification('Gap Detected', 'There are gaps in the schedule items. Please ensure there are no gaps before proceeding.')
                    return false
                }
            }
            return true
        },

        getValidScheduleItems() {
            return this.scheduleItems.filter(item => !item.removed && item.type !== 'gap').sort((a, b) => dayjs(a.start_dateTime).isBefore(dayjs(b.start_dateTime)) ? -1 : 1)
        },

        showNotification(response) {
            const { message, status } = response
            const notificationStore = useNotificationStore()
            notificationStore.setToastNotification(message, status)
        },

        handleError(error) {
            const notificationStore = useNotificationStore();
            const errorMessage = error.response ? error.response.data.message : error.message;
            let errorDetails = error.response && error.response.data.details ? error.response.data.details : '';

            // Check if errorDetails is a string and parse it to an object if necessary
            if (typeof errorDetails === 'string') {
                try {
                    errorDetails = JSON.parse(errorDetails);
                } catch (e) {
                    // If parsing fails, wrap the errorDetails in an object
                    errorDetails = { details: errorDetails };
                }
            }

            // Aggregate validation error messages with HTML styling
            let detailedErrorMessages = '<ul>';
            for (const [field, messages] of Object.entries(errorDetails)) {
                // Ensure messages is an array and join them into a string
                const messageText = Array.isArray(messages) ? messages.join(', ') : messages;
                detailedErrorMessages += `<li><strong>${field}:</strong> ${messageText}</li>`;
            }
            detailedErrorMessages += '</ul>';

            // Display general error message with aggregated details
            notificationStore.setGeneralServiceNotification('Validation Error', `${errorMessage}\n${detailedErrorMessages}`);

            this.error = errorMessage;
        },

        setPlaylistData(playlist) {
            console.log('playlist:', playlist)
            const userStore = useUserStore()

            const startTime = dayjs.utc(playlist.start_dateTime).tz(userStore.timezone).format('YYYY-MM-DDTHH:mm')
            const endTime = dayjs.utc(playlist.end_dateTime).tz(userStore.timezone).format('YYYY-MM-DDTHH:mm')

            this.id = playlist.id
            this.name = playlist.name
            this.description = playlist.description
            this.url = playlist.url
            this.type = playlist.type
            this.priority = playlist.priority
            this.repeat_mode = playlist.repeat_mode
            this.next_playlist_id = playlist.next_playlist_id
            this.startTime = startTime
            this.endTime = endTime

            console.log('playlist.playlist_items:', playlist.playlist_items)

            this.scheduleItems = playlist.playlist_items
                .filter(item => {
                    console.log('Filtering item:', item)
                    return item !== null && item !== undefined
                })
                .map(item => {
                    const mappedItem = {
                        id: item.id,
                        content_id: item.content_id,
                        content_type: item.content_type,
                        order: item.order,
                        media_type: item.media_type,
                        source_path: item.source_path,
                        source_type: item.source_type,
                        is_live: item.is_live,
                        is_scheduled: item.is_scheduled,
                        current_viewers_count: item.current_viewers_count,
                        max_viewers_count: item.max_viewers_count,
                        additional_sources: item.additional_sources,
                        custom_playback_options: item.custom_playback_options,
                        metadata: item.metadata,
                        has_played: item.has_played,
                        start_dateTime: item.start_dateTime,
                        end_dateTime: item.end_dateTime,
                        duration_minutes: item.duration_minutes,
                        type: item.type,
                        content: item.content,
                    }
                    console.log('Mapped item:', mappedItem)
                    return mappedItem
                })

            console.log('this.scheduleItems:', this.scheduleItems)
        },
        async fetchSchedules() {
            this.loadingSchedules = true
            this.clearError()
            const notificationStore = useNotificationStore()

            if (!this.startTime || !this.endTime) {
                notificationStore.setGeneralServiceNotification('Start and End Times Required', 'Please check the schedule start and end times.')
                this.loadingSchedules = false
                return
            }

            try {
                const response = await axios.get(`/api/schedules`, {
                    params: {
                        startTime: this.startTimeUTC,
                        endTime: this.endTimeUTC,
                    },
                })
                // Push new items to the existing array with added attributes
                this.scheduleItems.push(...response.data.items.map(item => ({
                    ...item,
                    removed: false,
                    is_scheduled: true
                })))
                const {message, status} = response.data
                notificationStore.setToastNotification(message, status)
                this.updateConflicts()
                this.loadingSchedules = false
                return response.data // return the data to the caller
            } catch (error) {
                this.error = error.response ? error.response.data.message : error.message
                notificationStore.setToastNotification(this.error, 'error')
                this.loadingSchedules = false
                throw error
            }
        },
        removeItem(id) {
            const item = this.scheduleItems.find(item => item.id === id)
            if (item) {
                item.removed = true
                this.scheduleItems = [...this.scheduleItems]  // Trigger reactivity
                this.updateConflicts()
            }
            this.clearError()
        },
        addItem(id) {
            const item = this.scheduleItems.find(item => item.id === id)
            if (item) {
                item.removed = false
                this.scheduleItems = [...this.scheduleItems]  // Trigger reactivity
                this.updateConflicts()
            }
            this.clearError()
        },
        removeAllItems() {
            this.scheduleItems = []
            this.clearError()
        },
        clearRemovedItems() {
            this.scheduleItems = this.scheduleItems.filter(item => !item.removed)
            this.clearError()
            this.recalculateIndexes()
        },
        recalculateIndexes() {
            this.scheduleItems = this.scheduleItems.map((item, index) => ({...item, index}))
        },
        updateConflicts() {
            const items = this.scheduleItems.filter(item => !item.removed)
            items.sort((a, b) => dayjs(a.start_dateTime).isBefore(dayjs(b.start_dateTime)) ? -1 : 1)

            for (let i = 0; i < items.length; i++) {
                const currentItem = items[i]
                currentItem.conflict = false
            }

            for (let i = 0; i < items.length; i++) {
                const currentItem = items[i]
                for (let j = i + 1; j < items.length; j++) {
                    const nextItem = items[j]
                    if (
                        dayjs(currentItem.start_dateTime).isBefore(dayjs(nextItem.end_dateTime)) &&
                        dayjs(currentItem.end_dateTime).isAfter(dayjs(nextItem.start_dateTime))
                    ) {
                        currentItem.conflict = true
                        nextItem.conflict = true
                    }
                }
            }
        },
        selectPlaylist(playlist) {
            this.selectedPlaylist = playlist
        },
        removePlaylist(playlistId) {
            router.delete(route('channelPlaylists.destroy', { channelPlaylist: playlistId }))
        },
        clearError() {
            this.error = null
        },
        resolveConflicts() {
            this.processing = true // Set processing flag
            let conflictsResolved

            do {
                conflictsResolved = false

                // Create a copy of the items array without removed items
                let items = this.scheduleItems.filter(item => !item.removed)

                for (let i = 1; i < items.length; i++) {
                    const currentItem = items[i]

                    if (!currentItem.conflict) continue

                    for (let j = 0; j < i; j++) {
                        const previousItem = items[j]

                        if (previousItem.removed) continue

                        // Check for conflict
                        const currentStart = dayjs(currentItem.start_dateTime)
                        const previousEnd = dayjs(previousItem.end_dateTime)
                        const previousStart = dayjs(previousItem.start_dateTime)

                        if (
                            currentStart.isSame(previousStart) ||
                            (currentStart.isBefore(previousEnd) && !currentStart.isSame(previousEnd))
                        ) {
                            // Resolve conflict based on priority and creation date
                            if (currentItem.priority > previousItem.priority) {
                                this.scheduleItems = this.scheduleItems.map(item =>
                                    item.id === currentItem.id ? {...item, removed: true} : item,
                                )
                                conflictsResolved = true
                                break
                            } else if (currentItem.priority < previousItem.priority) {
                                this.scheduleItems = this.scheduleItems.map(item =>
                                    item.id === previousItem.id ? {...item, removed: true} : item,
                                )
                                conflictsResolved = true
                                break
                            } else {
                                if (dayjs(currentItem.created_at).isAfter(dayjs(previousItem.created_at))) {
                                    this.scheduleItems = this.scheduleItems.map(item =>
                                        item.id === currentItem.id ? {...item, removed: true} : item,
                                    )
                                    conflictsResolved = true
                                    break
                                } else {
                                    this.scheduleItems = this.scheduleItems.map(item =>
                                        item.id === previousItem.id ? {...item, removed: true} : item,
                                    )
                                    conflictsResolved = true
                                    break
                                }
                            }
                        }
                    }

                    if (conflictsResolved) break
                }

            } while (conflictsResolved)

            this.updateConflicts()
            // console.log('Finished processing conflicts')
            this.processing = false // Clear processing flag
        },

        insertGaps() {
            this.clearError()
            // console.log('Start Time UTC:', this.startTimeUTC);
            // console.log('End Time UTC:', this.endTimeUTC);

            // Map schedule items to include dayjs objects for start and end times
            const items = this.scheduleItems
                .filter(item => !item.removed)
                .map(item => ({
                    ...item,
                    start: dayjs(item.start_dateTime),
                    end: dayjs(item.end_dateTime),
                }))

            // console.log('Items:', items);

            this.gaps = []

            if (items.length === 0) {
                // console.log('No scheduled items, creating a single gap for the entire period.');
                this.gaps.push({
                    type: 'gap',
                    start_dateTime: dayjs.utc(this.startTimeUTC).toISOString(),
                    end_dateTime: dayjs.utc(this.endTimeUTC).toISOString(),
                    duration_minutes: dayjs(this.endTimeUTC).diff(dayjs(this.startTimeUTC), 'minute'),
                    id: `gap-${dayjs.utc(this.startTimeUTC).toISOString()}-${dayjs.utc(this.endTimeUTC).toISOString()}`,
                    start: dayjs.utc(this.startTimeUTC),
                    end: dayjs.utc(this.endTimeUTC),
                })
            } else {
                this.checkForGapBeforeFirstItem(items)
                this.checkForGapsBetweenItems(items)
                this.checkForGapAfterLastItem(items)
            }

            // console.log('Gaps:', this.gaps);

            // Add gaps to the schedule items and sort the list by start dateTime
            this.scheduleItems = [...items, ...this.gaps].sort((a, b) => a.start.isBefore(b.start) ? -1 : 1)

            // console.log('Final Schedule Items:', this.scheduleItems);
        },


        checkForGapBeforeFirstItem(items) {
            // console.log('Checking for gap before the first item...')
            if (items.length > 0 && dayjs.utc(this.startTimeUTC).isBefore(items[0].start)) {
                const gapStart = dayjs.utc(this.startTimeUTC)
                const gapEnd = items[0].start
                const gapDuration = gapEnd.diff(gapStart, 'minute')
                // console.log(`Creating gap before the first item: ${gapStart.toISOString()} to ${gapEnd.toISOString()}, duration: ${gapDuration} minutes`)

                this.gaps.push({
                    type: 'gap',
                    start_dateTime: gapStart.toISOString(), // ISO 8601 string in UTC
                    end_dateTime: gapEnd.toISOString(), // ISO 8601 string in UTC
                    duration_minutes: gapDuration,
                    id: `gap-${gapStart.toISOString()}-${gapEnd.toISOString()}`,
                    start: gapStart,
                    end: gapEnd,
                })
            } else {
                // console.log('No gap before the first item.')
            }
        },

        checkForGapsBetweenItems(items) {
            // console.log('Checking for gaps between items...')
            for (let i = 0; i < items.length - 1; i++) {
                const currentItem = items[i]
                const nextItem = items[i + 1]

                const gapStart = currentItem.end
                const gapEnd = nextItem.start

                // console.log(`Current Item: ${currentItem.id}, End: ${currentItem.end.toISOString()}`)
                // console.log(`Next Item: ${nextItem.id}, Start: ${nextItem.start.toISOString()}`)
                // console.log(`Gap start: ${gapStart.toISOString()}, Gap end: ${gapEnd.toISOString()}`)

                if (gapStart.isBefore(gapEnd)) {
                    const gapDuration = gapEnd.diff(gapStart, 'minute')
                    // console.log(`Creating gap between items: ${gapStart.toISOString()} to ${gapEnd.toISOString()}, duration: ${gapDuration} minutes`)

                    this.gaps.push({
                        type: 'gap',
                        start_dateTime: gapStart.toISOString(), // ISO 8601 string in UTC
                        end_dateTime: gapEnd.toISOString(), // ISO 8601 string in UTC
                        duration_minutes: gapDuration,
                        id: `gap-${gapStart.toISOString()}-${gapEnd.toISOString()}`,
                        start: gapStart,
                        end: gapEnd,
                    })
                } else {
                    // console.log('No gap between these items.')
                }
            }
        },

        checkForGapAfterLastItem(items) {
            // console.log('Checking for gap after the last item...');
            if (items.length > 0) {
                const lastItemEnd = items[items.length - 1].end
                const endTime = dayjs.utc(this.endTimeUTC) // Ensure endTimeUTC is treated as UTC
                // console.log(`Last Item End: ${lastItemEnd.toISOString()}, End Time: ${endTime.toISOString()}`);

                // Check that last item ends before endTimeUTC
                if (lastItemEnd.isBefore(endTime)) {
                    const gapStart = lastItemEnd
                    const gapEnd = endTime
                    const gapDuration = gapEnd.diff(gapStart, 'minute')
                    // console.log(`Creating gap after the last item: ${gapStart.toISOString()} to ${gapEnd.toISOString()}, duration: ${gapDuration} minutes`);

                    this.gaps.push({
                        type: 'gap',
                        start_dateTime: gapStart.toISOString(), // ISO 8601 string in UTC
                        end_dateTime: gapEnd.toISOString(), // ISO 8601 string in UTC
                        duration_minutes: gapDuration,
                        id: `gap-${gapStart.toISOString()}-${gapEnd.toISOString()}`,
                        start: gapStart,
                        end: gapEnd,
                    })
                } else {
                    // console.log('No gap after the last item.');
                }
            } else {
                // console.log('No items to check for gaps after.');
            }
        },
        async fetchContent(contentType, maxDurationMinutes, startDateTime, page = 1, search = '') {
            this.loading = true
            this.clearError()
            const notificationStore = useNotificationStore()

            // Convert startDateTime from user's timezone to UTC
            const startDateTimeUTC = dayjs(startDateTime).tz(dayjs.tz.guess()).utc().format('YYYY-MM-DDTHH:mm:ss[Z]')

            // Log the parameters before making the request
            // console.log('Fetching content with parameters:');
            // console.log('Content Type:', contentType);
            // console.log('Max Duration Minutes:', maxDurationMinutes);
            // console.log('Start DateTime (User Timezone):', startDateTime);
            // console.log('Start DateTime (UTC):', startDateTimeUTC);
            // console.log('Page:', page);
            // console.log('Search Query:', search);

            try {
                const response = await axios.get('/admin/channel-playlist/get-content', {
                    params: {
                        type: contentType,
                        maxDurationMinutes: maxDurationMinutes,
                        start_dateTime: startDateTimeUTC,
                        page: page,
                        search: search,
                    },
                })
                console.log('Response data:', response.data)  // Log the response data
                this.contentItems = response.data.items
                this.currentPage = response.data.current_page
                this.totalPages = response.data.total_pages
                this.contentFetched = true
                const {message, status} = response.data
                notificationStore.setToastNotification(message, status)
            } catch (error) {
                this.error = error.response ? error.response.data.message : error.message
                console.error('Error fetching content:', this.error)
                notificationStore.setToastNotification(this.error, 'error')
            } finally {
                this.loading = false
            }
        },
        addContentToSchedule(content) {
            this.clearError()
            this.scheduleItems.push(content)
            this.scheduleItems = [...this.scheduleItems] // Trigger reactivity
            this.removeGap(content.start_dateTime) // Remove the gap after adding content
            this.insertGaps() // Update gaps after removing and adding content
        },
        removeGap(startDateTime) {
            // console.log('removing gap...')
            // console.log('start dateTime: ' + startDateTime)
            this.scheduleItems = this.scheduleItems.filter(item => !(item.type === 'gap' && item.start_dateTime === startDateTime))
        },
        resetContent() {
            this.contentItems = []
            this.contentFetched = false
            this.error = null
            this.currentPage = 1
            this.totalPages = 1
            this.searchQuery = ''
        },
        setSearchQuery(query) {
            this.searchQuery = query
        },
        setPage(page) {
            this.currentPage = page
        },
    },

    getters: {
        startTimeUTC: state => {
            return dayjs(state.startTime).utc().format('YYYY-MM-DDTHH:mm:ss[Z]')
        },
        endTimeUTC: state => {
            return dayjs(state.endTime).utc().format('YYYY-MM-DDTHH:mm:ss[Z]')
        },
        scheduleItemsWithUserTimezone(state) {
            const userStore = useUserStore()
            const items = state.scheduleItems.map(item => {
                return {
                    ...item,
                    start_dateTime: dayjs(item.start_dateTime).tz(userStore.timezone).format('YYYY-MM-DDTHH:mm:ssZ'),
                    end_dateTime: dayjs(item.end_dateTime).tz(userStore.timezone).format('YYYY-MM-DDTHH:mm:ssZ'),
                }
            })
            items.sort((a, b) => dayjs(a.start_dateTime).isBefore(dayjs(b.start_dateTime)) ? -1 : 1)
            return items
        },
        hasRemovedItems(state) {
            return state.scheduleItems.some(item => item.removed)
        },
        conflictCount(state) {
            return state.scheduleItems.filter(item => item.conflict && !item.removed).length
        },
        gapCount(state) {
            const items = state.scheduleItems
                .filter(item => !item.removed && item.type !== 'gap')
                .map(item => ({
                    start: dayjs(item.start_dateTime),
                    end: dayjs(item.end_dateTime),
                    conflict: item.conflict,
                }))

            let gaps = 0

            // console.log('Calculating gaps...');
            // console.log('Start Time UTC:', state.startTimeUTC);
            // console.log('End Time UTC:', state.endTimeUTC);
            // console.log('Items:', items);

            // If there are no items, the entire period is a single gap
            if (items.length === 0) {
                // console.log('No scheduled items, entire period is a gap.');
                return 1
            }

            // Check for gap before the first item
            if (dayjs.utc(state.startTimeUTC).isBefore(items[0].start)) {
                // console.log('Gap before the first item');
                gaps++
            } else {
                // console.log('No gap before the first item');
            }

            // Check for gaps between adjacent items
            for (let i = 0; i < items.length - 1; i++) {
                const currentItem = items[i]
                const nextItem = items[i + 1]

                // console.log(`Current Item: ${currentItem.start.toISOString()}, End: ${currentItem.end.toISOString()}`);
                // console.log(`Next Item: ${nextItem.start.toISOString()}, Start: ${nextItem.end.toISOString()}`);

                // Only consider gaps if there are no conflicts
                if (!currentItem.conflict && !nextItem.conflict && currentItem.end.isBefore(nextItem.start)) {
                    // console.log('Gap between items');
                    gaps++
                } else {
                    // console.log('No gap between these items');
                }
            }

            // Check for gap after the last item
            if (dayjs.utc(state.endTimeUTC).isAfter(items[items.length - 1].end)) {
                // console.log('Gap after the last item');
                gaps++
            } else {
                // console.log('No gap after the last item');
            }

            // console.log('Total gaps:', gaps);
            return gaps
        },


    },
})
