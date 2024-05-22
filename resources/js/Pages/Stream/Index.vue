<template>
  <Head title="Stream"/>
</template>

<script setup>
import { router } from '@inertiajs/vue3'
import { inject, onBeforeMount, onMounted, ref } from 'vue'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useUserStore } from '@/Stores/UserStore'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'

usePageSetup('stream')

const appSettingStore = useAppSettingStore()
const userStore = useUserStore()
const videoPlayerStore = useVideoPlayerStore()

// const getUserData = inject('getUserData', null)

appSettingStore.osd = true
appSettingStore.osdSlot.a = true
appSettingStore.osdSlot.b = true
appSettingStore.osdSlot.c = true
appSettingStore.osdSlot.d = true
videoPlayerStore.makeVideoFullPage()

// let reloadPage = () => {
//   if (appSettingStore.pageReload) {
//     appSettingStore.pageReload = false
//     window.location.reload(true);
//   }
// };

let props = defineProps({
  // getUserData: Boolean,
  video: Object,
  user: Object,
})

onBeforeMount(() => {
  // reloadPage()
})

// const userTimezone = ref('');

// This should be called as soon as the component mounts
// const getUserTimezone = () => {
//   userTimezone.value = Intl.DateTimeFormat().resolvedOptions().timeZone;
// };

// Call getUserTimezone early, possibly in onMounted
onMounted(async () => {
  // getUserTimezone();
  // if (props.getUserData) {
  //   await updateUserStore();
  // } else if (!getUserData) {
  //   await updateUserStore()
  // }
  // router.reload()
})
//
// async function updateUserStore() {
//   // Ensure the timezone is set
//   // if (!userTimezone.value) {
//   //   getUserTimezone();
//   // }
//
//   try {
//     const response = await axios.post('/getUserStoreData');
//     // Update the store with the response data
//     userStore.id = response.data.id;
//     appSettingStore.loggedIn = true
//     userStore.isAdmin = response.data.isAdmin
//     userStore.isCreator = response.data.isCreator
//     userStore.isNewsPerson = response.data.isNewsPerson
//     userStore.isVip = response.data.isVip
//     userStore.isSubscriber = response.data.isSubscriber
//     userStore.hasAccount = response.data.hasAccount
//     userStore.getUserDataCompleted = true
//     userStore.timezone = userTimezone.value;
//     console.log('get user data on Stream')
//     // Further logic
//     if (userStore.isCreator) {
//       userStore.prevUrl = '/dashboard';
//     } else {
//       userStore.prevUrl = '/stream';
//     }
//
//     // Now update the user timezone on the server
//     // await updateUserTimezone();
//   } catch (error) {
//     console.error(error);
//   }
// }
//
// const updateUserTimezone = async () => {
//   if (!userTimezone.value) return;
//
//   try {
//     const response = await axios.post('/users/update-timezone', { timezone: userTimezone.value });
//     console.log(response.data.message);
//   } catch (error) {
//     console.error(error.response ? error.response.data : error);
//   }
// };
</script>

