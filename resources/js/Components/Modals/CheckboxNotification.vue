<template>
    <input type="checkbox" id="my_modal_6" class="modal-toggle" v-model="notificationStore.active" />
    <div class="modal" role="dialog" v-if="notificationStore.active">
        <div class="modal-box">
            <h3 class="font-bold text-lg">{{ notificationStore.title }}</h3>
            <p class="py-4">{{ notificationStore.body }}</p>
            <div class="modal-action">
                <label for="my_modal_6" class="btn" @click="modalButtonAction">{{ notificationStore.buttonLabel }}</label>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useNotificationStore } from "@/Stores/NotificationStore.js"

let notificationStore = useNotificationStore();

function modalButtonAction() {
    if (notificationStore.onClickAction === 'redirect') {
        notificationStore.redirectAndClear()
    } else if (notificationStore.onClickAction === 'close') {
        notificationStore.closeAndClear()
    }
}

</script>
