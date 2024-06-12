// mixins/usePlaylistForm.js
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
    const name = ref(initialData.name || '')
    const description = ref(initialData.description || '')
    const url = ref(initialData.url || '')
    const type = ref(initialData.type || 'regular')
    const priority = ref(initialData.priority || 1)
    const repeat_mode = ref(initialData.repeat_mode || 'repeat_all')
    const next_playlist_id = ref(initialData.next_playlist_id || null)
    const scheduleItems = ref(initialData.scheduleItems || [])
    const error = ref(null)
    const selectedGapDuration = ref(0)
    const showModal = ref(false)
    const startDateTime = ref(initialData.startDateTime || '')

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

    const openAddContentModal = (gapItem) => {
        selectedGapDuration.value = gapItem.duration_minutes
        showModal.value = true
        startDateTime.value = gapItem.start_dateTime
    }

    const closeModal = () => {
        showModal.value = false
    }

    const fetchPlaylistsIfNeeded = async () => {
        if (repeat_mode.value === 'next_playlist' && store.playlists.length === 0) {
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
        name,
        description,
        url,
        type,
        priority,
        repeat_mode,
        next_playlist_id,
        scheduleItems,
        error,
        selectedGapDuration,
        showModal,
        startDateTime,
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
