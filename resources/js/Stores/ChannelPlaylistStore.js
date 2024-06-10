import { defineStore } from "pinia";
import dayjs from 'dayjs';
import timezone from 'dayjs/plugin/timezone';
import utc from 'dayjs/plugin/utc';
import { useNotificationStore } from '@/Stores/NotificationStore';
import { useUserStore } from '@/Stores/UserStore';

dayjs.extend(utc);
dayjs.extend(timezone);

const initialState = () => ({
    playlists: [],
    selectedPlaylist: null,
    scheduleItems: [],
    startTimeUTC: dayjs().format('YYYY-MM-DDTHH:mm'),
    endTimeUTC: dayjs().add(24, 'hour').format('YYYY-MM-DDTHH:mm'),
    loading: false,
    loadingSchedules: false,
    error: null,
})

export const useChannelPlaylistStore = defineStore('channelPlaylistStore', {
    state: initialState,
    actions: {
        reset() {
            // Reset the store to its original state (clear all data)
            Object.assign(this, initialState());
        },
        async fetchPlaylists() {
            this.loading = true;
            try {
                const response = await axios.get('/api/playlists');
                this.playlists = response.data;
                const {message, status} = response.data
                notificationStore.setToastNotification(message, status)
            } catch (error) {
                this.error = error.response ? error.response.data.message : error.message;
                notificationStore.setToastNotification(this.error, 'error')

            } finally {
                this.loading = false;
            }
        },
        async createPlaylist(playlist) {
            const notificationStore = useNotificationStore();

            // Check for conflicts
            if (this.scheduleItems.some(item => item.conflict)) {
                notificationStore.setGeneralServiceNotification('Conflict Detected', 'There are conflicts in the schedule items. Please resolve them before creating the playlist.');
                return false;
            }

            // Check for gaps
            const items = this.scheduleItems.filter(item => !item.removed).sort((a, b) => dayjs(a.start_dateTime).isBefore(dayjs(b.start_date_time)) ? -1 : 1);
            for (let i = 0; i < items.length - 1; i++) {
                if (dayjs(items[i].end_date_time).isBefore(dayjs(items[i + 1].start_dateTime))) {
                    notificationStore.setGeneralServiceNotification('Gap Detected', 'There are gaps in the schedule items. Please ensure there are no gaps before creating the playlist.');
                    return false;
                }
            }

            this.loading = true;
            try {
                const response = await axios.post('/api/playlists', playlist);
                this.playlists.push(response.data);
                const { message, status } = response.data;
                notificationStore.setToastNotification(message, status);
                return true;
            } catch (error) {
                this.error = error.response ? error.response.data.message : error.message;
                notificationStore.setToastNotification(this.error, 'error');
                return false;
            } finally {
                this.loading = false;
            }
        },
        async updatePlaylist(playlist) {
            this.loading = true;
            try {
                const response = await axios.put(`/api/playlists/${playlist.id}`, playlist);
                const index = this.playlists.findIndex(p => p.id === playlist.id);
                this.playlists[index] = response.data;
                const {message, status} = response.data
                notificationStore.setToastNotification(message, status)
            } catch (error) {
                this.error = error.response ? error.response.data.message : error.message;
                notificationStore.setToastNotification(this.error, 'error')
            } finally {
                this.loading = false;
            }
        },
        async fetchSchedules(startTimeUTC, endTimeUTC) {
            this.loadingSchedules = true;
            const notificationStore = useNotificationStore();
            try {
                const response = await axios.get(`/api/schedules`, { params: { startTime: startTimeUTC, endTime: endTimeUTC } });
                this.scheduleItems = response.data.items.map(item => ({ ...item, removed: false }));
                const {message, status} = response.data
                notificationStore.setToastNotification(message, status)
                this.scheduleItems = response.data.items
                this.updateConflicts();
                this.loadingSchedules = false;
                return response.data; // return the data to the caller
            } catch (error) {
                this.error = error.response ? error.response.data.message : error.message;
                notificationStore.setToastNotification(this.error, 'error');
                this.loadingSchedules = false;
                throw error;
            }
        },
        removeItem(id) {
            const item = this.scheduleItems.find(item => item.id === id);
            if (item) {
                item.removed = true;
                this.scheduleItems = [...this.scheduleItems];  // Trigger reactivity
                this.updateConflicts();
            }
        },
        addItem(id) {
            const item = this.scheduleItems.find(item => item.id === id);
            if (item) {
                item.removed = false;
                this.scheduleItems = [...this.scheduleItems];  // Trigger reactivity
                this.updateConflicts();
            }
        },
        clearRemovedItems() {
            this.scheduleItems = this.scheduleItems.filter(item => !item.removed);
            this.recalculateIndexes();
        },
        recalculateIndexes() {
            this.scheduleItems = this.scheduleItems.map((item, index) => ({ ...item, index }));
        },
        updateConflicts() {
            const items = this.scheduleItems.filter(item => !item.removed);
            items.sort((a, b) => dayjs(a.start_dateTime).isBefore(dayjs(b.start_dateTime)) ? -1 : 1);

            for (let i = 0; i < items.length; i++) {
                const currentItem = items[i];
                currentItem.conflict = false;
            }

            for (let i = 0; i < items.length; i++) {
                const currentItem = items[i];
                for (let j = i + 1; j < items.length; j++) {
                    const nextItem = items[j];
                    if (
                        dayjs(currentItem.start_dateTime).isBefore(dayjs(nextItem.end_dateTime)) &&
                        dayjs(currentItem.end_dateTime).isAfter(dayjs(nextItem.start_dateTime))
                    ) {
                        currentItem.conflict = true;
                        nextItem.conflict = true;
                    }
                }
            }
        },
        selectPlaylist(playlist) {
            this.selectedPlaylist = playlist;
        },
        clearError() {
            this.error = null;
        }
    },

    getters: {
        scheduleItemsWithUserTimezone(state) {
            const userStore = useUserStore();
            const items = state.scheduleItems.map(item => {
                const timezone = item.timezone || 'UTC';
                return {
                    ...item,
                    start_dateTime: dayjs(item.start_dateTime).tz(timezone).tz(userStore.timezone).format('YYYY-MM-DDTHH:mm:ss'),
                    end_date_time: dayjs(item.end_dateTime).tz(timezone).tz(userStore.timezone).format('YYYY-MM-DDTHH:mm:ss')
                };
            });
            items.sort((a, b) => dayjs(a.start_dateTime).isBefore(dayjs(b.start_dateTime)) ? -1 : 1);
            return items;
        },
        hasRemovedItems(state) {
            return state.scheduleItems.some(item => item.removed);
        },
        conflictCount(state) {
            return state.scheduleItems.filter(item => item.conflict && !item.removed).length;
        },
        gapCount(state) {
            const items = state.scheduleItems
                .filter(item => !item.removed)
                .map(item => ({
                    start: dayjs(item.start_dateTime),
                    end: dayjs(item.end_date_time)
                }))
                .sort((a, b) => a.start.isBefore(b.start) ? -1 : 1);

            let gaps = 0;
            let currentEnd = null;

            for (let i = 0; i < items.length; i++) {
                if (currentEnd && currentEnd.isBefore(items[i].start)) {
                    gaps++;
                }
                if (!currentEnd || items[i].end.isAfter(currentEnd)) {
                    currentEnd = items[i].end;
                }
            }

            return gaps;
        }
    }
});
