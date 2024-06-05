<template>
  <Head :title="creator.name"/>

  <div class="flex flex-col items-center gap-y-3 p-5 min-h-screen bg-white text-black pb-24">
    <div class="flex flex-col w-full max-w-7xl mx-auto">
      <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>

      <ShowCreatorsHeader :creator="creator"/>

      <CreatorsFundraisingGoal :creator="creator"/>
      <CreatorsNewsStories :creator="creator"/>
      <CreatorsTeamsSwiper :creator="creator"/>
    </div>

  </div>
</template>

<script setup>
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import Message from '@/Components/Global/Modals/Messages'
import CreatorsNewsStories from '@/Components/Pages/Creators/CreatorsNewsStories.vue'
import CreatorsTeamsSwiper from '@/Components/Pages/Creators/CreatorsTeamsSwiper.vue'
import CreatorsFundraisingGoal from '@/Components/Pages/Creators/CreatorsFundraisingGoal.vue'

import ShowCreatorsHeader from '@/Components/Pages/Creators/ShowCreatorsHeader.vue'


const appSettingStore = useAppSettingStore()

const props = defineProps({
  creator: Object,
})
</script>
