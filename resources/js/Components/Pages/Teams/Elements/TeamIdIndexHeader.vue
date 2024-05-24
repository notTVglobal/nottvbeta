<template>
  <div>


      <div class="flex flex-col px-5 items-end">
        <div v-if="props?.can?.manageTeam" class="flex flex-wrap-reverse justify-end gap-2 mb-2">
          <div>
            <button
                v-if="props?.can?.manageTeam"
                @click="appSettingStore.btnRedirect(`/teams/${props.team.slug}/manage`)"
                class="px-4 py-2 text-white bg-orange-600 hover:bg-orange-500 rounded-lg"
            >Back to<br/>
              Manage Team
            </button>
          </div>
        </div>
        <div v-if="props?.can?.editTeam" class="flex flex-wrap-reverse justify-end gap-2">
          <button

              @click="appSettingStore.btnRedirect(`/teams/${props.team.slug}/edit`)"
              class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
          >Edit
          </button>
        </div>
        <div>
          <button
              v-if="props?.user?.role_id === 4"
              @click="appSettingStore.btnRedirect(`/dashboard`)"
              class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
              hidden
          >Dashboard
          </button>
        </div>
      </div>

    <div class="flex flex-col justify-between pt-2 lg:pt-6 px-5">

      <div class="flex flex-row flex-wrap justify-between gap-2">
        <div class="flex flex-row w-full justify-center lg:justify-end mb-6 lg:mb-0">
          <button @click="router.visit('/teams')" class="btn btn-wide">Browse All Teams</button>
        </div>

        <div class="flex flex-row flex-wrap gap-2 justify-center mb-4">
<!--          <SingleImage :image="image" :alt="'team logo'" :class="'min-w-40 min-h-40 max-h-40 mr-4'"/>-->
          <SingleImageWithModal :image="image" :alt="'team logo'" :class="'min-w-40 min-h-40 max-h-40 mr-4'" class="transition-transform duration-300 ease-in-out transform hover:scale-105"/>
          <div class="flex flex-col items-start ">
            <h3 class="light:text-gray-900 dark:text-gray-50 inline-flex items-center text-3xl font-semibold relative uppercase">
              {{ team.name }}
            </h3>
            <SocialMediaBadgeLinks :socialMediaLinks="team.socialMediaLinks"/>
            <ShareButton />
          </div>

        </div>

      </div>

    </div>
  </div>
</template>
<script setup>
import { router } from '@inertiajs/vue3'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import SingleImage from '@/Components/Global/Multimedia/SingleImage'
import SocialMediaBadgeLinks from '@/Components/Global/Badges/SocialMediaBadgeLinks.vue'
import SingleImageWithModal from '@/Components/Global/Multimedia/SingleImageWithModal.vue'
import ShareButton from '@/Components/Global/UserActions/ShareButton.vue'

const appSettingStore = useAppSettingStore()

const props = defineProps({
  team: Object,
  image: Object,
  can: Object,
})
</script>
