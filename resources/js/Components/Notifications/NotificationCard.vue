<template>
    <div class="relative">
        <button v-if="userStore.notifications.length > 0"
                class="absolute z-50 bg-accent/30 hover:bg-accent/50 flex justify-right w-fit px-4 py-2 rounded right-0 top-3"
                @click.prevent="deleteNotification(notification.id)">
            <font-awesome-icon icon="fa-trash-can" />

        </button>
        <div class="flex flex-row py-6 border-b-2 border-accent border-opacity-25">

            <div class="w-fit">
                <img v-if="!notification.image.folder"
                     :src="'/storage/images/' + notification.image.name"
                     :alt="notification.image.name"
                     class="w-20 h-20 object-cover"/>

                <img v-if="notification.image.folder"
                     :src="notification.image.app_setting.cdn_endpoint + notification.image.cloud_folder + notification.image.folder + '/' + notification.image.name"
                     :alt="notification.image.name"
                     class="w-20 h-20 object-cover"/>
            </div>
            <div class="ml-4 lg:ml-8 w-5/6">
                <h3 class="font-bold text-lg"><button @click.prevent="visitUrl(notification.url)">{{ notification.title }}</button></h3>
                <p class="py-4">{{ notification.message }}</p>
            </div>
        </div>

    </div>

</template>

<script setup>
import {useUserStore} from "@/Stores/UserStore";
import {ref} from "vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {Inertia} from "@inertiajs/inertia";

let userStore = useUserStore(

)
let props = defineProps({
    notification: Object,
})

const notificationsDialog = ref(null);
const emit = defineEmits('closeModal')

const markAsRead = async (notificationId) => {
    // Make an API request to mark a notification as read
    await fetch(`/notifications/${notificationId}/mark-as-read`, { method: 'PUT' });
    // if there are more notifications, then go to the next notification page.
    if (userStore.newNotifications > 0) {
        userStore.newNotifications--;
    }
    userStore.removeNotificationById(notificationId)

    // if this is the last notification then close the modal
    if (userStore.notifications.length === 0) {
        emit('closeModal')
        notificationsDialog.value = document.getElementById('notifications');
        notificationsDialog.value.removeAttribute('open');
    }
};

const deleteNotification = async (notificationId) => {
    try {
        await fetch(`/notifications/${notificationId}`, { method: 'DELETE' });
        // Handle success, e.g., remove the deleted notification from your store
        userStore.removeNotificationById(notificationId)
        if (userStore.newNotifications > 0) {
            userStore.newNotifications--;
        }
        // if this is the last notification then close the modal
        if (userStore.notifications.length === 0) {
            emit('closeModal')
            notificationsDialog.value = document.getElementById('notifications');
            notificationsDialog.value.removeAttribute('open');
        }
    } catch (error) {
        // Handle any errors that occur during the deletion
    }
};

const visitUrl = (url) => {
    emit('closeModal')
    Inertia.visit(url)
}


</script>
