<template>
  <div>


      <div class="flex flex-col px-5 items-end">
        <div v-if="can?.manageTeam" class="flex flex-wrap-reverse justify-end gap-2 mb-2">
          <div>
            <button
                v-if="can?.manageTeam"
                @click="appSettingStore.btnRedirect(`/teams/${team.slug}/manage`)"
                class="px-4 py-2 text-white bg-orange-600 hover:bg-orange-500 rounded-lg"
            >Back to<br/>
              Manage Team
            </button>
          </div>
        </div>
        <div v-if="can?.editTeam" class="flex flex-wrap-reverse justify-end gap-2">
          <button

              @click="appSettingStore.btnRedirect(`/teams/${team.slug}/edit`)"
              class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
          >Edit
          </button>
        </div>
        <div>
          <button
              v-if="userStore.isCreator"
              @click="appSettingStore.btnRedirect(`/dashboard`)"
              class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
              hidden
          >Dashboard
          </button>
        </div>
      </div>

    <div class="flex flex-col justify-between pt-2 lg:pt-6 px-5">
      <div class="flex flex-col xl:flex-row flex-wrap items-start justify-between gap-2">
        <div class="flex flex-col xl:flex-row items-start justify-start gap-2 mb-4 w-full xl:w-auto">
          <div class="flex-shrink-0 self-start">
            <SingleImageWithModal :image="team.image"
                                  :alt="'team logo'"
                                  :class="'min-w-40 min-h-40 max-h-40 mb-4 xl:mb-0'"
                                  class="transition-transform duration-300 ease-in-out transform hover:scale-105"/>
          </div>
          <div class="flex flex-col items-center xl:items-start justify-center w-full xl:w-auto text-center xl:text-left">
            <h3 class="light:text-gray-900 dark:text-gray-50 text-3xl font-semibold uppercase">
              {{ team.name }}
            </h3>
            <div>
              <SocialMediaBadgeLinks :socialMediaLinks="team.socialMediaLinks" class="my-2 xl:my-0"/>
            </div>
            <ShareButton class="mt-2 xl:mt-2" :model="team"/>
          </div>
        </div>
        <div class="flex w-full xl:w-auto justify-center xl:justify-end mb-6">
          <button @click="router.visit('/teams')" class="btn btn-wide">Browse All Teams</button>
        </div>
      </div>
    </div>




  </div>
</template>
<script setup>
import { router } from '@inertiajs/vue3'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useTeamStore } from '@/Stores/TeamStore'
import { useUserStore } from '@/Stores/UserStore'
import SocialMediaBadgeLinks from '@/Components/Global/Badges/SocialMediaBadgeLinks.vue'
import SingleImageWithModal from '@/Components/Global/Multimedia/SingleImageWithModal.vue'
import ShareButton from '@/Components/Global/UserActions/ShareButton.vue'
import { computed } from 'vue'

const appSettingStore = useAppSettingStore()
const teamStore = useTeamStore()
const userStore = useUserStore()

// Map store state to local computed properties
const team = computed(() => teamStore.team || {});
const can = computed(() => teamStore.can || {});

</script>
