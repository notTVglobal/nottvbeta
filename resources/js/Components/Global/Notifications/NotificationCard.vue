<template>
  <div class="relative">
    <button v-if="userStore.newNotifications > 0"
            class="absolute z-50 bg-accent/30 hover:bg-accent/50 flex justify-right w-fit mr-2 px-4 py-2 rounded right-0 bottom-3"
            @click.prevent="deleteNotification(props.notification.id)">
      <font-awesome-icon icon="fa-trash-can"/>

    </button>
    <div class="flex flex-row py-6 mb-3 border-b-2 border-accent border-opacity-25">

      <div class="w-fit">

        <div v-if="props.notification.image">
          <img v-if="!props.notification.image.folder"
               :src="'/storage/images/' + props.notification.image.name"
               :alt="props.notification.image.name"
               class="w-20 h-20 object-cover"/>

          <img v-if="props.notification.image.folder"
               :src="props.notification.image.app_setting.cdn_endpoint + props.notification.image.cloud_folder + props.notification.image.folder + '/' + props.notification.image.name"
               :alt="props.notification.image.name"
               class="w-20 h-20 object-cover"/>

        </div>
        <div v-else>
          🔔
        </div>

      </div>
      <div class="ml-4 lg:ml-8 pr-2 w-5/6 justify-left">
        <button @click.prevent="visitUrl(props.notification.url)"><h3 class="font-bold text-lg text-left justify-left">
          {{ props.notification.title }}</h3></button>
        <p class="py-4"><span v-html="truncatedMessage"/></p>
        <p class="py-1 text-xs font-light">{{ timeAgo }}</p>
      </div>
    </div>

  </div>

</template>

<script setup>
import { Inertia } from "@inertiajs/inertia"
import { computed, ref } from "vue"
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"
import { useTimeAgo } from '@vueuse/core'
import { useUserStore } from "@/Stores/UserStore"

const userStore = useUserStore()

let props = defineProps({
  notification: Object,
})

let timeAgo = useTimeAgo(props.notification.created_at)

const notificationsDialog = ref(null);
const emit = defineEmits('closeModal')

// Computed property to truncate the message
const truncatedMessage = computed(() => {
  const message = props.notification.message || ''

  if (message.length <= 500) {
    return message;
  } else {
    return message.slice(0, 500) + '...';
  }
});

// const markAsRead = async (notificationId) => {
//     // Make an API request to mark a notification as read
//     await fetch(`/notifications/${notificationId}/mark-as-read`, { method: 'PUT' });
//     // if there are more notifications, then go to the next notification page.
//     if (userStore.newNotifications > 0) {
//         userStore.newNotifications--;
//     }
//     userStore.removeNotificationById(notificationId)
//
//     // if this is the last notification then close the modal
//     if (userStore.newNotifications === 0) {
//         emit('closeModal')
//         notificationsDialog.value = document.getElementById('notifications');
//         notificationsDialog.value.removeAttribute('open');
//     }
// };

const deleteNotification = async (notificationId) => {
  try {
    await axios.delete(`/notifications/${notificationId}`, {method: 'DELETE'}).then((response) => {
      // Handle success, e.g., remove the deleted notification from your store
      userStore.removeNotificationById(notificationId)
      if (userStore.newNotifications > 0) {
        userStore.newNotifications--;
      }
      // if this is the last notification then close the modal
      if (userStore.newNotifications === 0) {
        emit('closeModal')
        notificationsDialog.value = document.getElementById('notifications');
        notificationsDialog.value.removeAttribute('open');
      }
    })

  } catch (error) {
    // Handle any errors that occur during the deletion
  }
};

const visitUrl = (url) => {
  emit('closeModal')
  Inertia.visit(url)
}


</script>
