<template>
  <div>

    <div class="container mx-auto px-4 gap-y-3 flex justify-end">

      <div v-if="can?.editShow || can?.manageShow">
        <div class="flex flex-col">
          <div class="flex flex-end flex-wrap-reverse justify-end gap-2 mr-4 my-4">
            <div>
              <button
                  @click="appSettingStore.btnRedirect(`/teams/${team.slug}/manage`)"
                  class="px-4 py-2 mr-2 h-fit text-white bg-orange-600 hover:bg-orange-500 rounded-lg"
              >Back to<br/>
                Team Page
              </button>
              <button
                  v-if="can?.manageShow"
                  @click="appSettingStore.btnRedirect(`/shows/${show.slug}/manage`)"
                  class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
              >Back to<br/>
                Manage Show
              </button>
            </div>
          </div>
          <div class="flex flex-end flex-wrap-reverse justify-end gap-2 mr-4">
            <button
                v-if="can?.editShow"
                @click="appSettingStore.btnRedirect(`/shows/${show.slug}/edit`)"
                class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
            >Edit Show
            </button>
          </div>
        </div>
      </div>

      <button
          v-if="!can?.manageShow"
          @click="appSettingStore.btnRedirect(`/teams/${team.slug}`)"
          class="px-4 py-2 mr-2 h-fit text-white bg-orange-600 hover:bg-orange-500 rounded-lg shadow-md"
      >Visit the<br/>
        Team Page
      </button>

    </div>

    <ShowIdIndexHeaderContents :show="show" :team="team"/>
  </div>
</template>
<script setup>
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import ShowIdIndexHeaderContents from '@/Components/Pages/Shows/Elements/ShowIdIndexHeaderContents.vue'

const appSettingStore = useAppSettingStore()

defineProps({
  show: Object,
  team: Object,
  can: Object,
})
</script>