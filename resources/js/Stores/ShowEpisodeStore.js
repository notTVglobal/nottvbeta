import { defineStore } from 'pinia'
import { useUserStore } from '@/Stores/UserStore'
import dayjs from 'dayjs'
import utc from 'dayjs/plugin/utc'
import timezone from 'dayjs/plugin/timezone'

// Extend Day.js with the necessary plugins
dayjs.extend(utc)
dayjs.extend(timezone)

const initialState = () => ({
    episode: {},
    show: {},
    team: {},
    creativeCommons: {},
    saveNoteProcessing: Boolean,
    errorMessage: '',
    isLive: false,
    liveScheduledStartTime: '',
    liveMistStreamName: '',
    isScheduled: null,
    isUpdatingSchedule: false,
    updatedBy: null,
    isSaving: null,
    loadingUpdatingStatus: false, // to show a loader
    leader: null, // for handling functions on the Echo broadcast channel
    isUploading: false,
})

export const useShowEpisodeStore = defineStore('showEpisodeStore', {
    state: initialState,
    actions: {
        reset() {
            // Reset the store to its original state (clear all data)
            Object.assign(this, initialState())
        },
        initializeShowEpisode(showEpisode, show = null, team = null, creativeCommons = null) {
            // console.log('initializeShow')
            let meta = {}
            try {
                if (showEpisode.meta) {
                    meta = typeof showEpisode.meta === 'string' ? JSON.parse(showEpisode.meta) : showEpisode.meta
                }
            } catch (error) {
                console.error('Error parsing meta:', error)
            }

            this.$patch({
                episode: {
                    id: showEpisode.id,
                    ulid: showEpisode.ulid,
                    name: showEpisode.name,
                    slug: showEpisode.slug,
                    description: showEpisode.description,
                    notes: showEpisode.notes,
                    episodeNumber: showEpisode.episode_number,
                    status: showEpisode.status,
                    release_year: showEpisode.release_year,
                    release_dateTime: showEpisode.release_dateTime, // keep this in UTC time in our State, the getter converts it to the user's timezone.
                    scheduled_release_dateTime: showEpisode.scheduled_release_dateTime, // keep this in UTC time in our State, the getter converts it to the user's timezone.
                    copyright_year: showEpisode.copyright_year,
                    creative_commons: showEpisode.creative_commons,
                    mist_stream_id: showEpisode.mist_stream_id,
                    youtube_url: showEpisode.youtube_url,
                    video_embed_code: showEpisode.video_embed_code,
                    video: showEpisode.video,
                    image: showEpisode.image,
                },
                show: show,
                team: team,
                creativeCommons: creativeCommons,

                isScheduled: meta.isScheduled ?? false,
                isSaving: meta.isSaving ?? false,
                isUpdatingSchedule: meta.isUpdatingSchedule ?? null,
                updatedBy: meta.updatedBy ?? null,
            })

        },
        setLeader(user) {
            this.leader = user
        },
        resetLeader() {
            this.leader = null
            // Reset other states...
        },
        async checkIsLive() {
            try {
                const response = await axios.get(`/api/showEpisodes/${this.slug}/check-live`)
                this.isLive = response.data.isLive
                this.liveScheduledStartTime = response.data.liveScheduledStartTime
                this.liveMistStreamName = response.data.mistStreamName
            } catch (error) {
                console.error('Error checking if episode is live:', error)
            }
        },

        async updateEpisode() {
            // TODO: Move the submit() from the Edit page here.
            //   update the form errors, notifications.
            //   add locking logic like the show manage page.
        },
        setReleaseDateTime(newDateTime) {
            // Convert newDateTime to UTC time
            this.episode.release_dateTime = dayjs(newDateTime).utc().format()
            this.episode.scheduled_release_dateTime = null
        },
        setScheduledReleaseDateTime(newDateTime) {
            // Convert newDateTime to UTC time
            this.episode.scheduled_release_dateTime = dayjs(newDateTime).utc().format()
            this.episode.release_dateTime = null
        },
        cancelScheduledRelease() {
            this.episode.scheduled_release_dateTime = null
        }

    },

    getters: {
        scheduleReleaseDateTimeInUserTz(state) {
            const userStore = useUserStore()
            if (state.episode.scheduled_release_dateTime) {
                return dayjs(state.episode.scheduled_release_dateTime).tz(userStore.timezone).format()
            } else
                return null
        },
        releaseDateTimeInUserTz(state) {
            const userStore = useUserStore()
            if (state.episode.release_dateTime) {
                return dayjs(state.episode.release_dateTime).tz(userStore.timezone).format()
            } else
                return null
        },
        formattedReleaseDateTime(state) {
            const userStore = useUserStore()
            if (state.episode.release_dateTime) {
                return userStore.formatLongDateTimeFromUtcToUserTimezone(state.episode.release_dateTime)
            } else
                return null

        },
        formattedScheduledReleaseDateTime(state) {
            const userStore = useUserStore()
            // return dayjs.utc(dateTime).tz(userStore.timezone).format('dddd MMMM D [at] h:mm a')
            if (state.episode.scheduled_release_dateTime) {
                return userStore.formatLongDateTimeFromUtcToUserTimezone(state.episode.scheduled_release_dateTime)
            } else
                return null

        }
    },

})
