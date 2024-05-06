<template>

  <Head :title="props.show.name"/>

  <div id="topDiv" class="place-self-center flex flex-col">
    <div class="bg-gray-900 text-white px-5 pt-6 min-h-screen">

      <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>

      <ShowIdIndexHeader :show="show" :team="team" :can="can"/>

      <ShowIdIndexMain :show="show" :team="team" :episodes="episodes" :creators="creators"/>

    </div>
  </div>

</template>

<script setup>
import { onMounted } from 'vue'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import Message from '@/Components/Global/Modals/Messages.vue'
import ShowIdIndexHeader from '@/Components/Pages/Shows/Elements/ShowIdIndexHeader.vue'
import ShowIdIndexMain from '@/Components/Pages/Shows/Elements/ShowIdIndexMain.vue'

usePageSetup('showsShow')

const appSettingStore = useAppSettingStore()

let props = defineProps({
  show: Object,
  episodes: Object,
  creators: Object,
  team: Object,
  can: Object,
})

onMounted(() => {
  const topDiv = document.getElementById('topDiv')
  topDiv.scrollIntoView()
})

</script>



