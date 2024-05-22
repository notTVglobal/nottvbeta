<template>
  <Head title="Teams"/>

  <div class="place-self-center flex flex-col gap-y-3">
    <div id="topDiv" class="min-h-screen bg-gray-700 text-gray-50 dark:bg-gray-700 dark:text-gray-50 p-5">

      <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>

      <div v-if="props.can.viewCreator" class="flex justify-end flex-wrap-reverse gap-x-2 pt-6">

        <Link v-if="can.createTeam" :href="`/teams/create`">
          <button
              class="bg-green-600 hover:bg-green-500 text-white px-4 py-2 rounded disabled:bg-gray-400"
          >Add Team
          </button>
        </Link>
        <DashboardButton />
      </div>

      <TeamsIndexGrid :teams ="teams" :filters="filters"/>


    </div>
  </div>

</template>

<script setup>
import { router } from '@inertiajs/vue3'
import { ref, watch } from 'vue'

import throttle from 'lodash/throttle'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import Message from '@/Components/Global/Modals/Messages'
import SingleImage from '@/Components/Global/Multimedia/SingleImage'
import Pagination from '@/Components/Global/Paginators/Pagination'
import TeamsIndexGrid from '@/Components/Pages/Teams/Elements/TeamsIndexGrid.vue'
import DashboardButton from '@/Components/Global/Buttons/DashboardButton.vue'

usePageSetup('teams')

const appSettingStore = useAppSettingStore()

let props = defineProps({
  teams: Object,
  filters: Object,
  can: Object,
})

let search = ref(props.filters.search)

watch(search, throttle(function (value) {
  router.get('/teams', {search: value}, {
    preserveState: true,
    replace: true,
  })
}, 300))

</script>


