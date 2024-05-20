<template>
  <div>
    <!--        OLD STORIES PLACEHOLDER-->
    <section class="mt-10 px-8">
      <div class="flex w-full h-fit flex-wrap justify-between align-baseline">
        <h2 class="text-center text-xl md:text-2xl font-medium my-auto align-middle pl-6 uppercase">News Stories</h2>

        <div class="my-auto">
          <div class="relative">
            <input v-model="search" type="search" class="bg-gray-50 text-black text-sm rounded-full
                            focus:outline-none focus:shadow w-64 pl-8 px-3 py-1" placeholder="Search...">
            <div class="absolute top-0 flex items-center h-full ml-2">
              <svg class="fill-current text-gray-400 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                <path
                    d="M456.69 421.39 362.6 327.3a173.81 173.81 0 0 0 34.84-104.58C397.44 126.38 319.06 48 222.72 48S48 126.38 48 222.72s78.38 174.72 174.72 174.72A173.81 173.81 0 0 0 327.3 362.6l94.09 94.09a25 25 0 0 0 35.3-35.3ZM97.92 222.72a124.8 124.8 0 1 1 124.8 124.8 124.95 124.95 0 0 1-124.8-124.8Z"/>
              </svg>

            </div>
          </div>
        </div>

        <div class="my-auto pr-6">
          <div class="flex justify-end ">
            <button
                v-if="can.createNewsStory"
                @click="appSettingStore.btnRedirect(`newsStory/create`)"
                class="bg-green-600 hover:bg-green-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400"
            >Create News Story
            </button>
          </div>
        </div>
      </div>

      <NewsStoriesList :newsStories="newsStories" :can="can"/>

    </section>
    <dialog id="newsStoryStatusChangeModal" class="modal">
      <div class="modal-box h-fit bg-white text-black">
        <h2 class="text-center mb-2">Change the News Story Status:</h2>
        <div v-for="(status, key) in newsStore.newsStoryStatuses" :key="key" class="text-center">
          <div class="btn btn-wide my-1" @click="newsStore.changeNewsStoryStatus(status.id)">{{ status.name }}</div>
        </div>
      </div>

      <form method="dialog" class="modal-backdrop">
        <button>close</button>
      </form>
    </dialog>
  </div>
</template>
<script setup>
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useNewsStore } from '@/Stores/NewsStore'
import NewsStoriesList from '@/Components/Pages/Newsroom/Layout/NewsStoriesList.vue'
import { ref, watch } from 'vue'
import throttle from 'lodash/throttle'
import { Inertia } from '@inertiajs/inertia'

const appSettingStore = useAppSettingStore()
const newsStore = useNewsStore()

const props = defineProps({
  newsStories: Object,
  can: Object,
  filters: Object,
})

let search = ref(props.filters.search)

watch(search, throttle(function (value) {
  Inertia.get('/newsroom', {search: value}, {
    preserveState: true,
    replace: true,
  })
}, 300))
</script>