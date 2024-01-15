import { defineStore } from 'pinia';

export const useDashboardStore = defineStore('dashboard', {
    // State
    state: () => ({
        currentNotificationType: 'default'
    }),

    // Actions
    actions: {
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
