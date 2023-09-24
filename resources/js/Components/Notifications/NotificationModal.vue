<template>
    <div>
        <dialog id="notifications" class="modal">
            <div class="modal-box bg-gray-900">
                <NotificationsContainer @closeModal="closeModalFunction" :key="userStore.notificationsKey" />

            </div>
            <form ref="closeModal" method="dialog" class="modal-backdrop">
                <button>close</button>
            </form>
        </dialog>
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
const notificationsDialog = ref(null);

const fetchNotifications = async () => {
    // Make an API request to fetch notifications
    const response = await fetch(`/notifications`);
    const data = await response.json();
    userStore.notifications = data.notifications;
};

// const deleteNotification = async (notificationId) => {
//     // Make an API request to mark a notification as read
//     await fetch(`/notifications/${notificationId}/mark-as-read`, { method: 'PUT' });
//     // if there are more notifications, then go to the next notification page.
//     if (userStore.newNotifications > 0) {
//         userStore.newNotifications--;
//     }
//     // if this is the last notification then close the modal
//     closeModal.value.submit();
// };

function closeModalFunction() {
    // Handle the custom event
    closeModal.value.submit();
    // notificationsDialog.value = document.getElementById('notifications');
    // notificationsDialog.value.removeAttribute('open');
}

onMounted(() => {
    fetchNotifications();
});

// nextTick(() => {
//     // This code runs after the DOM has been updated.
//     fetchNotifications();
// });

</script>

<style scoped>
.unread {
    font-weight: bold;
}
</style>
