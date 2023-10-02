<template>
    <div>
        <dialog id="notifications" class="modal">
            <div class="modal-box bg-gray-900 border-2 border-accent">
                <NotificationsContainer @closeModal="closeModalFunction" :key="userStore.notificationsKey" />

            </div>
            <form ref="closeModal" method="dialog" class="modal-backdrop">
                <button>close</button>
            </form>
        </dialog>
    </div>
</template>

<script setup>
import { onMounted, ref} from "vue"
import { useUserStore } from "@/Stores/UserStore"
import NotificationsContainer from "@/Components/Notifications/NotificationsContainer.vue"

let userStore = useUserStore();

const notifications = ref([]);
const closeModal = ref(null);
const notificationsDialog = ref(null);

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

function closeModalFunction() {
    closeModal.value.submit();
}

onMounted(() => {
    fetchNotifications();
});

</script>
