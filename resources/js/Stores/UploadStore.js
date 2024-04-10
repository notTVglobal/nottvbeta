import { defineStore } from 'pinia'

const initialState = () => ({
    videoId: null,
    uploadStatus: 'idle', // Possible values: 'idle', 'uploading', 'processing', 'completed', 'error'
})

export const useUploadStore = defineStore('uploadStore', {
    state: initialState,
    actions: {
        reset() {
            // Reset the store to its original state (clear all data)
            Object.assign(this, initialState())
        },
        setVideoId(id) {
            this.videoId = id;
        },
        setUploadStatus(status) {
            this.uploadStatus = status;
        },
    },
})