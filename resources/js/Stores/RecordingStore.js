import { defineStore } from "pinia";
import axios from "axios";
import dayjs from "dayjs";
import utc from 'dayjs/plugin/utc'
import timezone from 'dayjs/plugin/timezone'
import { useUserStore } from "@/Stores/UserStore";
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore';
import { useAppSettingStore } from '@/Stores/AppSettingStore';
import { useNowPlayingStore } from '@/Stores/NowPlayingStore';
import { useShowStore } from '@/Stores/ShowStore';

dayjs.extend(utc)
dayjs.extend(timezone)

const initialState = () => ({
    recordings: [],
    pagination: {
        currentPage: 1,
        lastPage: 1,
        total: 0,
    },
    selectedRecording: null,
    nowPlayingRecording: null,
    nowPlayingRecordingId: null,
    isLoading: false,
    showCopyMessage: false,
    copyMessage: '',
});

export const useRecordingStore = defineStore('recordingStore', {
    state: initialState,
    actions: {
        async fetchRecordings(page = 1) {
            this.isLoading = true;
            const showStore = useShowStore();
            const showId = showStore.show?.id;

            if (!showId) {
                console.error("No show ID available");
                this.isLoading = false;
                return;
            }

            try {
                const response = await axios.get(`/api/recordings`, {
                    params: {
                        show_id: showId,
                        page: page
                    }
                });
                this.recordings = response.data.data;
                this.pagination = {
                    currentPage: response.data.current_page,
                    lastPage: response.data.last_page,
                    total: response.data.total,
                };
            } catch (error) {
                console.error("Failed to fetch recordings:", error);
            } finally {
                this.isLoading = false;
            }
        },
        async updateRecording(meta) {
            this.isLoading = true;

            try {
                const response = await axios.patch(`/api/recordings/${this.selectedRecording.id}`, {
                    meta: meta,
                });
                const updatedRecording = response.data;

                // Update the recordings state with the updated recording
                const index = this.recordings.findIndex(recording => recording.id === updatedRecording.id);
                if (index !== -1) {
                    this.recordings[index] = updatedRecording;
                }
            } catch (error) {
                console.error("Failed to update recording:", error);
            } finally {
                this.isLoading = false;
            }
        },
        reset() {
            Object.assign(this, initialState());
        },
        formatDateInUserTimezone(date) {
            const userStore = useUserStore();
            return userStore.formatDateInUserTimezone(date);
        },
        formatTimeFromDateInUserTimezone(date) {
            const userStore = useUserStore();
            return userStore.formatTimeFromDateInUserTimezone(date);
        },
        formatDuration(totalMilliseconds) {
            let seconds = Math.floor(totalMilliseconds / 1000);
            let minutes = Math.floor(seconds / 60);
            const hours = Math.floor(minutes / 60);
            seconds = seconds % 60;
            minutes = minutes % 60;
            const paddedHours = hours.toString().padStart(2, '0');
            const paddedMinutes = minutes.toString().padStart(2, '0');
            const paddedSeconds = seconds.toString().padStart(2, '0');
            return `${paddedHours}h ${paddedMinutes}m ${paddedSeconds}s`;
        },
        setSelectedRecording(recording) {
            this.selectedRecording = recording;
        },
        clearSelectedRecording() {
            this.selectedRecording = null;
        },
        async openVideo() {
            // this.selectedRecording = recording;
            const videoPlayerStore = useVideoPlayerStore();
            const appSettingStore = useAppSettingStore();
            const nowPlayingStore = useNowPlayingStore();
            const showStore = useShowStore();
            const show = showStore.show;

            const source = {
                mediaType: 'recording',
                recording: {
                    source: this.selectedRecording.playback_stream_name,
                    sourceType: 'video/mp4',
                },
            };
            try {
                await videoPlayerStore.loadNewVideo(source);
                this.nowPlayingRecordingId = this.selectedRecording.id;
                appSettingStore.toggleOttInfo();
                nowPlayingStore.setActiveMedia(source.mediaType, {
                    primaryName: show.name,
                    secondaryName: `${this.formatDateInUserTimezone(this.selectedRecording.start_time)} ${this.formatTimeFromDateInUserTimezone(this.selectedRecording.start_time)} Recording`,
                    description: this.selectedRecording.comment || null,
                    primaryUrl: `shows/${show.slug}`,
                    image: show.image,
                });
            } catch (error) {
                console.error('Error loading new video:', error);
            }
        },
        downloadRecording() {
            if (!this.selectedRecording) return;

            const url = this.selectedRecording.download_url;
            const downloadLink = document.createElement('a');
            downloadLink.href = url;
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
        },
        shareRecording(shareUrl) {
            navigator.clipboard.writeText(shareUrl).then(() => {
                this.copyMessage = 'Video share URL copied!';
                this.showCopyMessage = true;
                setTimeout(() => {
                    this.showCopyMessage = false;
                }, 1000); // Hide after 1 second
            });
        },
    },
    getters: {
        formattedRecordings: (state) => {
            const userStore = useUserStore();
            return state.recordings.map(recording => ({
                ...recording,
                start_date_local: dayjs.utc(recording.start_dateTime).tz(userStore.timezone).format('ddd DD MMM'),
                start_time_local: dayjs.utc(recording.start_dateTime).tz(userStore.timezone).format('h:mm a'),
                end_time_local: dayjs.utc(recording.end_dateTime).tz(userStore.timezone).format('h:mm a'),
            }));
        },

    },
});
