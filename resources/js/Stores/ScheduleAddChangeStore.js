import { defineStore } from "pinia";

const initialState = () => ({

})

export const useWelcomeStore = defineStore('welcomeStore', {
    state: initialState,
    actions: {
        reset() {
            // Reset the store to its original state (clear all data)
            Object.assign(this, initialState())
        },

    }
})