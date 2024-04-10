import { defineStore } from "pinia";

const initialState = () => ({
    inviteCode: '',
    inviteUlid: '',
    isLoading: false,
    registrationAllowed: false,
})

export const useInviteStore = defineStore('inviteStore', {
    state: initialState,
    actions: {
        reset() {
            // Reset the store to its original state (clear all data)
            Object.assign(this, initialState());
        },
        async registrationAccessCheck() {
            if (this.isLoading === true) {
                return
            }
            this.isLoading = true; // Assuming there's an isLoading data property for loading state
            try {
                console.log('check access: ' + this.inviteUlid)
                const response = await axios.post(`/invite/${this.inviteUlid}/check-registration-access/`, {
                    inviteCode: this.inviteCode
                });

                if (response.data.success) {
                    // Process success (e.g., navigating to a new page or showing success message)
                    this.isLoading = false;
                    this.registrationAllowed = true;
                    return true;
                } else {
                    // Handle known failure (e.g., showing an error message from the server)
                    this.isLoading = false;
                    this.registrationAllowed = false;
                    this.errorMessage = response.data.message || 'Invite code not valid'; // Assuming an errorMessage data property
                    return false;
                }
            } catch (error) {
                // Handle unexpected errors
                console.error('Error in registrationAccessCheck:', error.message);
                this.isLoading = false;
                this.registrationAllowed = false;
                this.errorMessage = error.response && error.response.data.message ? error.response.data.message : 'An unexpected error occurred';
                return false;
            }
        }
    },

})
