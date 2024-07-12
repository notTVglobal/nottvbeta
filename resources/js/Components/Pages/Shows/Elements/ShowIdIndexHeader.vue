<template>
  <div>

    <div class="container mx-auto px-4 gap-y-3 flex justify-end">

      <div v-if="can?.editShow || can?.manageShow">
        <div class="flex flex-col">
          <div class="flex flex-end flex-wrap-reverse justify-end gap-2 mr-4 my-4">
            <div>
              <button
                  v-if="can?.manageShow"
                  @click="appSettingStore.btnRedirect(`/shows/${show.slug}/manage`)"
                  class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
              >
                <font-awesome-icon :icon="['fas', 'tv']" class="hover:text-blue-800 mr-1" /> Manage Show
              </button>
            </div>
          </div>
        </div>
      </div>

      <div v-if="!can?.manageShow" class="flex flex-row w-full justify-center lg:justify-end pb-6 lg:pb-0">
        <button @click="appSettingStore.btnRedirect(`/teams/${team.slug}`)" class="btn btn-wide">Visit the Team Page</button>
      </div>

    </div>

    <ShowIdIndexHeaderContents :show="show" :team="team"/>
  </div>
</template>
<script setup>
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import ShowIdIndexHeaderContents from '@/Components/Pages/Shows/Elements/ShowIdIndexHeaderContents.vue'
import { router } from '@inertiajs/vue3'

const appSettingStore = useAppSettingStore()

defineProps({
  show: Object,
  team: Object,
  can: Object,
})
</script>
