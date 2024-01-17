<template>

  <Head :title="props.creator.name"/>

  <div class="place-self-center flex flex-col gap-y-3">
    <div id="topDiv" class="bg-white text-black p-5 mb-10">

      <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>

      <div class="flex justify-between mb-6">
        <h1 class="text-2xl pb-3">{{ props.creator.name }}</h1>
        <Link href="/shows" class="text-blue-500 text-sm ml-2">Go back</Link>
      </div>
      <p>
        <img :src="props.creator.profile_photo_url"/>
      </p>

    </div>
  </div>

</template>

<script setup>
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from "@/Stores/AppSettingStore"
import Message from "@/Components/Global/Modals/Messages"

const appSettingStore = useAppSettingStore()

let props = defineProps({
  creator: Object,
});

usePageSetup(`creator/${props.creator.slug}`)

</script>
