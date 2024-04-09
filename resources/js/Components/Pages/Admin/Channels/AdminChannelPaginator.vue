<template>
  <div class="flex flex-row justify-center my-6 w-full">
    <!--                  <div class="join grid grid-cols-2">-->
    <!--                    <button class="join-item btn btn-sm btn-outline" @click="adminStore.prevChannelsPage">Previous page</button>-->
    <!--                    <button class="join-item btn btn-sm btn-outline" @click="adminStore.nextChannelsPage">Next</button>-->
    <!--                  </div>-->
    <div class="join">
      <input
          v-for="page in adminStore.totalChannelsPages"
          :key="page"
          type="radio"
          name="options"
          :value="page"
          v-model="adminStore.currentChannelsPage"
          @change="() => adminStore.setChannelsPage(page)"
          class="join-item btn btn-square !bg-none"
          :aria-label="`${page}`"
      />
    </div>
  </div>
</template>

<script setup>
import { useAdminStore } from '@/Stores/AdminStore'
const adminStore = useAdminStore()
</script>
