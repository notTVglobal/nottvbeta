<template>
  <Head title="Teams"/>

  <div class="place-self-center flex flex-col">
    <div id="topDiv" class="min-h-screen bg-gray-700 text-gray-50 dark:bg-gray-700 dark:text-gray-50">


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
import TeamsIndexGrid from '@/Components/Pages/Browse/Layout/TeamsIndexGrid.vue'
import DashboardButton from '@/Components/Global/Buttons/DashboardButton.vue'

usePageSetup('browse.index')

const appSettingStore = useAppSettingStore()

let props = defineProps({
  teams: Object,
  filters: Object,
  can: Object,
})

let search = ref(props.filters.search)

watch(search, throttle(function (value) {
  router.get('/browse', {search: value}, {
    preserveState: true,
    replace: true,
  })
}, 300))

</script>


