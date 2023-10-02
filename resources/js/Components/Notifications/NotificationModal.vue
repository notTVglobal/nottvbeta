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

function closeModalFunction() {
    closeModal.value.submit();
}


</script>
