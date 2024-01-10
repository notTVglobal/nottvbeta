import { defineStore } from "pinia";
import { Inertia } from "@inertiajs/inertia";

// the purpose of the notificationStore is to allow the system/user
// to clear out their notifications... instead of using only page props
// which are more difficult to clear. We can update this store with
// new page props when it's appropriate, and clear the notifications
// here regardless if the page props persists.

export let useNotificationStore = defineStore('notificationStore', {
    state() {
        return {
            title: '',
            body: '',
            buttonLabel: 'OKAY',
            // use the primaryUri to ensure this notification is only shown
            // on the correct page.
            uri: '',
            active: false,
            // onClickAction, close or redirect
            onClickAction: '',
            redirectPageUri: '',
        };
    },

    actions: {
        clearNotification() {
            this.title = '';
            this.body = '';
            this.buttonLabel = 'OKAY';
            this.uri = '';
            this.redirectPageUri = '';
            this.onClickAction = '';
        },
        redirectAndClear() {
            this.active = false;
            Inertia.visit(this.redirectPageUri);
            this.clearNotification();
        },
        closeAndClear() {
            this.active = false;
            this.clearNotification();
        },
        // use this to open the @/Components/Modals/DialogNotification
        // it only takes a title and body
        openDialogNotification() {
            const modal = document.getElementById('dialogNotificationModal');
            if (modal) {
                modal.showModal();
            }
        }
    },

})
