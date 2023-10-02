<template>
    <div>
        <!-- Check if notifications are loading or empty -->
        <div v-if="isLoading || notifications.length === 0">
            <!-- Render a loading message or placeholder content -->
            <p v-if="isLoading">Loading notifications...</p>
            <p v-else>No notifications available.</p>
        </div>

        <!-- Check if notifications are available and not empty -->
        <div v-else>
            <div v-for="notification in userStore.notifications.slice().reverse()" :key="notification.id">
                <NotificationCard :notification="notification" @closeModal="closeModalFunction"/>
                <!-- <button @click="markAsRead(notification.id)" v-if="!notification.read">Mark as Read</button> -->
            </div>
        </div>
    </div>
</template>

<script setup>
import {nextTick, onBeforeMount, onMounted, onUpdated, ref} from "vue";
import {useUserStore} from "@/Stores/UserStore";
import NotificationCard from "@/Components/Notifications/NotificationCard.vue";
import Pagination from "@/Components/PaginationDark.vue";
import NotificationsContainer from "@/Components/Notifications/NotificationsContainer.vue";

let userStore = useUserStore();

const isLoading = ref(true); // Initialize loading state
let notifications = ref([]);
const closeModal = ref(null);

const emit = defineEmits('closeModal')

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

</script>
