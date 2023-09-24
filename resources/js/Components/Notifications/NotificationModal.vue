<template>
    <div>
        <dialog id="notifications" class="modal">
            <div class="modal-box bg-gray-900">
                <Button v-if="notifications.length > 0"
                        class="absolute z-50 bg-blue-600 hover:bg-blue-500 flex justify-right w-fit px-4 py-2 rounded right-0 top-0"
                        @click.prevent="deleteNotification">
                    X
                </Button>
                <Button v-if="notifications.length > 0"
                        class="absolute z-50 font-bold flex justify-right w-fit px-4 py-2 rounded right-0 bottom-0"
                        @click.prevent="nextNotification">
                    >
                </Button>
                <Button v-if="notifications.length > 0"
                        class="absolute z-50 font-bold flex justify-left w-fit px-4 py-2 rounded left-0 bottom-0"
                        @click.prevent="prevNotification">
                    &lt;
                </Button>
                <div v-for="notification in notifications" :key="notification.id">
                    <div :class="{ 'unread': !notification.read }">{{ notification.message }}</div>
                    <NotificationCard />
                    <button @click="markAsRead(notification.id)" v-if="!notification.read">Mark as Read</button>
                </div>
                <span v-if="notifications.length === 0">No notifications.</span>

            </div>
            <form method="dialog" class="modal-backdrop">
                <button>close</button>
            </form>
        </dialog>
    </div>
</template>

<script setup>
import {onMounted, ref} from "vue";
import NotificationCard from "@/Components/Notifications/NotificationCard.vue";

const notifications = ref([]);

const fetchNotifications = async () => {
    // Make an API request to fetch notifications
    const response = await fetch(`/notifications`);
    const data = await response.json();
    notifications.value = data.notifications;
};

const markAsRead = async (notificationId) => {
    // Make an API request to mark a notification as read
    await fetch(`/notifications/${notificationId}/mark-as-read`, { method: 'PUT' });
    notifications.value = notifications.value.map((n) => {
        if (n.id === notificationId) {
            n.read = true;
        }
        return n;
    });
};

onMounted(() => {
    fetchNotifications();
});

function deleteNotification() {

}
function nextNotification() {

}
function prevNotification() {

}
</script>

<style scoped>
.unread {
    font-weight: bold;
}
</style>
