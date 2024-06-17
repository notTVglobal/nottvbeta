<template>
  <Head :title="pageTitle">
    <meta property="og:url" :content="ogUrl" />
    <meta property="og:type" :content="ogType" />
    <meta property="og:title" :content="ogTitle" />
    <meta property="og:description" :content="ogDescription" />
    <meta property="og:image" :content="ogImage" />
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@yourusername">
    <meta name="twitter:creator" content="@creatorusername">
    <meta name="twitter:title" content="Your Page Title">
    <meta name="twitter:description" content="A brief description of your page content.">
    <meta name="twitter:image" content="https://example.com/image.jpg">
    <meta name="twitter:image:alt" content="A description of the image for accessibility.">
  </Head>

  <div id="topDiv" class="place-self-center flex flex-col">
    <div class="bg-gray-900 text-white px-5 pt-6 min-h-screen">

      <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>

      <ShowIdIndexHeader :show="show" :team="team" :can="can"/>

      <ShowIdIndexMain :show="show" :team="team" :episodes="episodes" :creators="creators"/>

    </div>
  </div>

</template>

<script setup>
import { computed, onMounted } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useTeamStore } from '@/Stores/TeamStore'
import Message from '@/Components/Global/Modals/Messages.vue'
import ShowIdIndexHeader from '@/Components/Pages/Shows/Elements/ShowIdIndexHeader.vue'
import ShowIdIndexMain from '@/Components/Pages/Shows/Elements/ShowIdIndexMain.vue'

usePageSetup('showsShow')

const appSettingStore = useAppSettingStore()
const teamStore = useTeamStore()
const page  = usePage().props

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
  teamStore.initializeTeam({...props.team})
})

const pageTitle = computed(() => props.show.name);
const ogUrl = computed(() => `${page.appUrl}${page.currentPath}`);
const ogType = computed(() => 'video.tv_show');
const ogTitle = computed(() => props.show.name);
// Truncate the description if it exceeds 300 characters
const ogDescription = computed(() => {
  const description = props.show.description;
  const maxLength = 300;
  return description.length > maxLength ? `${description.substring(0, maxLength)}...` : description;
});
const ogImage = computed(() => {
  const image = props.show.image;
  if (image) {
    const { cdn_endpoint, cloud_folder, name, placeholder_url } = image;
    if (cdn_endpoint && cloud_folder && name) {
      return `${cdn_endpoint}${cloud_folder}${name}`;
    } else if (placeholder_url) {
      return placeholder_url;
    }
  }
  return null;
});
</script>



