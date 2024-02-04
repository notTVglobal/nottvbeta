import { defineStore } from 'pinia';

const initialState = () => ({
    apiRequest: [],
    challenge: [],
    status: [],
    apiResponse: [],
    apiActiveStreams: [],
    mistUsername: [],
    mistPassword: [],
    mistStatus: Boolean,
    mistDisplayPushForm: Boolean,
    mistDisplay: String,
    mistNewHashedPassword: [],
});

export const useMistStore = defineStore('mistStore', {
    state: initialState,
    actions: {
        reset() {
            // Reset the store to its original state (clear all data)
            Object.assign(this, initialState());
        },
        // add additional actions here
    }
});
