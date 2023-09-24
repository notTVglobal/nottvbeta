<template>
    <div>

        <div v-for="notification in userStore.notifications" :key="notification.id">

            <NotificationCard :notification="notification" @closeModal="closeModalFunction"/>
            <!--                    <button @click="markAsRead(notification.id)" v-if="!notification.read">Mark as Read</button>-->
        </div>
        <span v-if="userStore.notifications.length === 0">No notifications.</span>
    </div>

</template>

<script setup>
import {nextTick, onMounted, onUpdated, ref} from "vue";
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
    userStore.notifications = data.notifications;
};


onMounted(() => {
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
