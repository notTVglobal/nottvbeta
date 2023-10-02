<template>
    <div>
        <div v-if="userStore.newNotifications === 0">
            No notifications.
        </div>
        <div v-else>
            <div v-for="notification in notifications.slice().reverse()" :key="notification.id">
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

const notifications = ref([]);
const closeModal = ref(null);

const emit = defineEmits('closeModal')

const fetchNotifications = async () => {
    // Make an API request to fetch notifications
    const response = await fetch(`/notifications`);
    const data = await response.json();
    // userStore.notifications = data.notifications;
    if (data.notifications && Array.isArray(data.notifications)) {
        userStore.newNotifications = data.notifications.length;
    } else {
        // Handle the case where notifications are missing or not an array
        userStore.newNotifications = 0; // or some other default value or error handling logic
    }
};


onBeforeMount(() => {
    fetchNotifications();
});

function closeModalFunction() {
    // Handle the custom event
    emit('closeModal')
    // closeModal.value.submit();
    // notificationsDialog.value = document.getElementById('notifications');
    // notificationsDialog.value.removeAttribute('open');
}




// nextTick(() => {
//     // This code runs after the DOM has been updated.
//     fetchNotifications();
// });

</script>
