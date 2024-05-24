import { defineStore } from 'pinia'
import { router } from '@inertiajs/vue3'

// the purpose of the notificationStore is to allow the system/user
// to clear out their notifications... instead of using only page props
// which are more difficult to clear. We can update this store with
// new page props when it's appropriate, and clear the notifications
// here regardless if the page props persists.

// tec21: 2024-01-17 this is silly. Just keep this logic in either the UserStore
// or the DashboardStore depending on whether notifications are going to be on
// the dashboard or the navBar Notification bell.

const initialState = () => ({
    title: '',
    body: '',
    confirmation: null,
    buttonLabel: 'OKAY',
    popUpModalTitle: '',
    popUpModalMessage: '',
    showPopUpModal: false, // a work in progress.... look for it in the Admin/Settings and it's used a couple other places... it needs to be moved to the App/Layout and then a function added here to document get by id... and here we need a state to hold the id... and then we can have a function to close it by id...
    // use the primaryUri to ensure this notification is only shown
    // on the correct page.
    uri: '',
    active: false,
    // onClickAction, close or redirect
    onClickAction: '',
    redirectPageUri: '',
    generalServiceNotification: null, // put a title and a body in here, the modal opens in AppLayout
    showGeneralServiceNotification: false, // used with the generalServiceNotification title and body, the modal opens in AppLayout
    showConfirmNotificationModal: false, // used with the confirmNotificationModal title and body, the modal opens in AppLayout
    toastNotificationVisible: false,
    toastNotificationMessage: '',
    toastNotificationStatus: '', // success, error, info, warning
    toastNotificationTimeout: 5000, // default timeout to 5 seconds
    timeoutId: null, // Add this line
    errorMessage: null,
})

export const useNotificationStore = defineStore('notificationStore', {
    state: initialState,
    actions: {
        reset() {
            // Reset the store to its original state (clear all data)
            Object.assign(this, initialState())
        },
        setErrorMessage(error) {
            this.errorMessage = error
        },
        clearErrorMessage() {
            this.errorMessage = null
        },
        setGeneralServiceNotification(title, body) {
            // console.log('open modal')
            this.generalServiceNotification = {
                'title': title,
                'body': body,
            }
            document.getElementById('generalServiceNotificationModal').showModal()
            this.showGeneralServiceNotification = true
        },
        clearGeneralServiceNotification() {
            this.generalServiceNotification = null
            this.showGeneralServiceNotification = false
        },
        setConfirmNotification(title, body) {
            this.title = title
            this.body = body
            const modal = document.getElementById('confirmNotificationModal')
            if (modal && modal.showModal) {
                modal.showModal()
            }
        },
        clearConfirmNotification() {
            this.title = ''
            this.body = ''
            this.confirmation = null
            // document.getElementById('confirmNotificationModal').closeModal()
        },
        setConfirmationNotification(value) {
            this.confirmation = value
        },
        setToastNotification(message, status, customTimeoutValue = 5000) {
            // Ensure any existing timeout is cleared before setting a new one
            if (this.timeoutId) {
                clearTimeout(this.timeoutId)
            }

            this.toastNotificationVisible = true
            this.toastNotificationMessage = message
            this.toastNotificationStatus = status

            // Use either the customTimeoutValue or the default
            this.toastNotificationTimeout = customTimeoutValue

            // Automatically reset the notification after the specified timeout
            this.timeoutId = setTimeout(() => {
                this.resetToastNotification()
            }, this.toastNotificationTimeout)
        },
        resetToastNotification() {
            this.toastNotificationVisible = false
            this.toastNotificationMessage = ''
            this.toastNotificationStatus = ''
            this.timeoutId = null // Reset the timeout ID
        },
        clearNotification() {
            this.title = ''
            this.body = ''
            this.buttonLabel = 'OKAY'
            this.uri = ''
            this.redirectPageUri = ''
            this.onClickAction = ''
        },
        redirectAndClear() {
            this.active = false
            router.visit(this.redirectPageUri)
            this.clearNotification()
        },
        closeAndClear() {
            this.active = false
            this.clearNotification()
        },
        // use this to open the @/Components/Global/Modals/DialogNotification
        // it only takes a title and body
        openDialogNotification(title, message) {
            const modal = document.getElementById('dialogNotificationModal')
            if (modal) {
                this.title = title
                this.body = message
                modal.showModal()
            }
        },
        showSuccess(title, message) {
            this.title = title
            this.message = message
            this.showModal = true
        },
        showError(error) {
            let errorMessage = 'An unexpected error occurred. Please try again later.'
            if (error.response && error.response.data && error.response.data.message) {
                errorMessage = error.response.data.message
            } else if (error.message) {
                errorMessage = error.message
            }
            this.title = 'Error'
            this.message = errorMessage
            this.showModal = true
        },
        closeModal() {
            this.showModal = false
        },
    },
    getters: {
        formattedErrorMessage: (state) => {
            // Check if errorMessage is not empty
            if (Object.keys(state.errorMessage).length > 0) {
                // Extract all error messages and flatten them into a single array
                const messages = Object.values(state.errorMessage).flat()
                // Join all messages into a single string, separated by a space or any delimiter you prefer
                return messages.join('. ') // Adjust based on how you want to display them
            }
            return '' // Return an empty string if there is no error message
        },
    },

})
