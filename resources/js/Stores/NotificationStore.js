import { defineStore } from "pinia";

// the purpose of the notificationStore is to allow the system/user
// to clear out their notifications... instead of using only page props
// which are more difficult to clear. We can update this store with
// new page props when it's appropriate, and clear the notifications
// here regardless if the page props persists.

export let useNotificationStore = defineStore('notificationStore', {
    state() {
        return {
            primaryTitle: '',
            primaryMessage: '',
            primaryButtonLabel: '',
            // use the primaryUri to ensure this notification is only shown
            // on the correct page.
            primaryUri: '',
        };
    },

    actions: {
        //
    },

})
