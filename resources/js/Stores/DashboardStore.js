import { defineStore } from 'pinia';

const initialState = () => ({
    currentNotificationType: 'default'
})

export const useDashboardStore = defineStore('dashboard', {
    state: initialState,
    actions: {
        reset() {
            // Reset the store to its original state (clear all data)
            Object.assign(this, initialState())
        },
        setNotificationType(type) {
            this.currentNotificationType = type;
        }
    },

    // Getters (if needed)
    getters: {
        isTeamTransferNotification: (state) => state.currentNotificationType === 'teamTransfer'
        // Add other getters as necessary
    }
});
