<template>
  <div class="my-10">

    <section :class="panelClasses"
             class="min-h-56 grid grid-cols-1 my-8 mx-2 md:mx-10 m-auto justify-center bg-gray-100 shadow-2xl shadow-cyan-950 rounded-lg p-5 relative text-black">

      <div class="flex flex-col text-center h-full w-full items-center">
        <component :is="currentNotificationComponent"/>
        <div v-if="!currentNotificationComponent" class="flex h-full text-gray-600 items-center">No notifications
          available.
        </div>
      </div>

      <div class="notification-controls absolute bottom-0 left-0 right-0 flex">
        <button @click="setNotificationType('teamTransfer')"
                class="flex-1 bg-blue-500 hover:bg-blue-600 text-white py-2 transition duration-300 ease-in-out rounded-bl-lg">
          <!-- Optional icon or text -->
        </button>
        <button @click="setNotificationType('promo')"
                class="flex-1 bg-green-500 hover:bg-green-600 text-white py-2 transition duration-300 ease-in-out">
          <!-- Optional icon or text -->
        </button>
        <button @click="setNotificationType('weather')"
                class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white py-2 transition duration-300 ease-in-out rounded-br-lg">
          <!-- Optional icon or text -->
        </button>
      </div>
    </section>

  </div>


</template>

<script setup>
import { ref, computed } from 'vue'
import { useDashboardStore } from "@/Stores/DashboardStore"
import TeamTransferAlert from "@/Components/Pages/Dashboard/Elements/DashboardNotification/Alerts/TeamTransferAlert"
import PromotionalPoster from "@/Components/Pages/Dashboard/Elements/DashboardNotification/Promotions/PromotionalPoster"
import WeatherWidget from "@/Components/Pages/Dashboard/Elements/DashboardNotification/Widgets/WeatherWidget"

const AdashboardStore = useDashboardStore()

const setNotificationType = (type) => {
  dashboardStore.setNotificationType(type)
};

const currentNotificationComponent = computed(() => {
  switch (dashboardStore.currentNotificationType) {
    case 'teamTransfer':
      return TeamTransferAlert;
    case 'promo':
      return PromotionalPoster; // Now handling the promotional poster
    case 'weather':
      return WeatherWidget; // Now handling the promotional poster
    default:
      return null;
  }
})

const panelClasses = computed(() => {
  switch (dashboardStore.currentNotificationType) {
    case 'teamTransfer':
      return 'bg-gray-800 border border-blue-700 text-white';
    case 'promo':
      return 'bg-green-100 border border-green-700';
    case 'weather':
      return 'bg-yellow-100 border border-yellow-700';
    default:
      return 'bg-gray-100 border border-gray-300';
  }
});

</script>

<style scoped>
/* Additional styling if needed */
</style>
