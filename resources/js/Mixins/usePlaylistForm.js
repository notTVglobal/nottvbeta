import { ref } from 'vue'
import dayjs from 'dayjs'
import { useChannelPlaylistStore } from '@/Stores/ChannelPlaylistStore'
import { useNotificationStore } from '@/Stores/NotificationStore'
import { useUserStore } from '@/Stores/UserStore'

export function usePlaylistForm(initialData = {}) {
    const store = useChannelPlaylistStore()
    const notificationStore = useNotificationStore()
    const userStore = useUserStore()

    const isSmallScreen = ref(userStore.isSmallScreen)
    const selectedGapDuration = ref(0)
    const startDateTime = ref('')

    const getListItemClass = (conflict, removed) => {
        if (removed) {
            return isSmallScreen.value
                ? 'flex flex-col p-2 border-b border-gray-200 space-y-2 bg-gray-100'
                : 'p-4 space-y-2 bg-gray-100'
        }

        return isSmallScreen.value
            ? 'flex flex-col p-2 border-b border-gray-200 space-y-2'
            : `p-4 space-y-2 ${conflict ? 'bg-red-100' : ''}`
    }

    const clearStartDateTime = () => {
        store.startTime = ''
    }

    const setStartDateTimeNow = () => {
        store.startTime = dayjs().format('YYYY-MM-DDTHH:mm')
    }

    const clearEndDateTime = () => {
        store.endTime = ''
    }

    const setEndDateTimeNow = () => {
        store.endTime = dayjs().format('YYYY-MM-DDTHH:mm')
    }

    const removeItem = (id) => {
        store.removeItem(id)
    }

    const addItem = (id) => {
        store.addItem(id)
    }

    const clearRemovedItems = () => {
        store.clearRemovedItems()
    }

    const resolveConflicts = () => {
        store.resolveConflicts()
    }

    const fetchSchedules = () => {
        store.fetchSchedules()
    }

    const openAddContentModal = (gapItem) => {
        selectedGapDuration.value = gapItem.duration_minutes
        store.showModal = true
        startDateTime.value = gapItem.start_dateTime
        console.log('Opening modal with startDateTime:', startDateTime.value) // Add log to verify startDateTime
    }
    const closeModal = () => {
        store.showModal = false // Use the store's reactive property
    }

    const fetchPlaylistsIfNeeded = async () => {
        if (store.repeat_mode === 'next_playlist' && store.playlists.length === 0) {
            await store.fetchPlaylists()
        }
    }

    const validateTimes = () => {
        if (dayjs(store.startTime).isSameOrAfter(dayjs(store.endTime))) {
            notificationStore.setGeneralServiceNotification('Invalid Date Time', 'End time must be after start time.')
            return false
        }
        if (store.conflictCount > 0) {
            notificationStore.setGeneralServiceNotification('Conflicts Present', 'Please resolve conflicts before proceeding.')
            return false
        }
        if (store.gapCount > 0) {
            notificationStore.setGeneralServiceNotification('Gaps Present', 'Please fill gaps before proceeding.')
            return false
        }
        if (store.scheduleItems.length === 0) {
            notificationStore.setGeneralServiceNotification('No Schedule Items', 'Please add schedule items before proceeding.')
            return false
        }
        return true
    }

    return {
        store,
        notificationStore,
        userStore,
        isSmallScreen,
        selectedGapDuration,
        startDateTime,
        fetchSchedules,
        getListItemClass,
        clearStartDateTime,
        setStartDateTimeNow,
        clearEndDateTime,
        setEndDateTimeNow,
        removeItem,
        addItem,
        clearRemovedItems,
        resolveConflicts,
        openAddContentModal,
        closeModal,
        fetchPlaylistsIfNeeded,
        validateTimes,
    }
}
