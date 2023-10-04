<template>
    <div>
        <div v-if="userStore.newNotifications > 1" class="flex justify-end mr-2 text-accent hover:text-accent/80 underline cursor-pointer">
            <button @click.prevent="deleteAllNotifications">Delete all</button>
        </div>

        <!-- Check if notifications are loading or empty -->
        <div v-if="isLoading || userStore.newNotifications === 0">
            <!-- Render a loading message or placeholder content -->
            <p v-if="isLoading">Loading notifications...</p>
            <p v-else>No notifications available.</p>
        </div>

        <!-- Check if notifications are available and not empty -->
        <div v-else>
            <div v-for="notification in filteredNotifications.slice().reverse()" :key="notification.id">
                <div v-if="notification"> <!-- Check if notification exists -->
                    <NotificationCard :notification="notification" @closeModal="closeModalFunction"/>
                    <!-- <button @click="markAsRead(notification.id)" v-if="!notification.read">Mark as Read</button> -->
                </div>
            </div>

        </div>
        <div v-if="userStore.newNotifications > 1" class="flex justify-end mr-2 text-accent hover:text-accent/80 underline cursor-pointer">
            <button @click.prevent="deleteAllNotifications">Delete all</button>
        </div>
    </div>
</template>

<script setup>
import {computed, nextTick, onBeforeMount, onMounted, onUpdated, ref} from "vue";
import {useUserStore} from "@/Stores/UserStore";
import NotificationCard from "@/Components/Notifications/NotificationCard.vue";
import Pagination from "@/Components/PaginationDark.vue";
import NotificationsContainer from "@/Components/Notifications/NotificationsContainer.vue";

let userStore = useUserStore();

const isLoading = ref(true); // Initialize loading state
let notifications = ref([]);
const closeModal = ref(null);
const notificationsDialog = ref(null);

const emit = defineEmits('closeModal')

const filteredNotifications = computed(() => {
    return userStore.notifications.filter(notification => !!notification);
});

setTimeout(() => {
    notifications.value = userStore.notifications;
    isLoading.value = false;
}, 2000); // Simulated 2-second delay

function closeModalFunction() {
    // Handle the custom event
    emit('closeModal')
    // closeModal.value.submit();
    // notificationsDialog.value = document.getElementById('notifications');
    // notificationsDialog.value.removeAttribute('open');
}

const deleteAllNotifications = async () => {
    try {
        await fetch(`/notifications`, { method: 'DELETE' });

        // Remove deleted notifications from userStore.notifications
        userStore.notifications = userStore.notifications.filter(notification => !!notification);

        // Handle success, e.g., remove the deleted notification from your store
        if (userStore.newNotifications > 0) {
            userStore.notifications = [];
            // notifications = []
            userStore.newNotifications = 0;
        }

        // close the modal
        closeModalFunction()
        notificationsDialog.value = document.getElementById('notifications');
        notificationsDialog.value.removeAttribute('open');

    } catch (error) {
        // Handle any errors that occur during the deletion
    }
};



</script>
