<template>
  <div class="flex flex-col px-5 border-b border-blue-50">
    <div class="-my-2 overflow-x-hidden sm:-mx-6 lg:-mx-8">
      <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">


        <div class="mb-6 p-5">

          <div v-if="!episodes.length" class="text-center">
            <div class="text-6xl mb-4">📺✨</div>
            <h2 class="text-2xl font-bold mb-2">Stay Tuned!</h2>
            <p class="text-lg">Exciting episodes are coming soon. Stay tuned and be ready for some amazing content!</p>
            <button @click="appSettingStore.btnRedirect(`/shows/`)" class="mt-4 px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white font-bold rounded">
              Explore More Shows
            </button>
          </div>
          <ShowEpisodesList v-if="episodes.length" :episodes="episodes" :show="show"/>

          <div hidden class="container mx-auto px-4 mb-12">
            <div class="w-full bg-gray-800 text-2xl p-4 mb-4">CREDITS</div>

            <div class="flex flex-row flex-wrap justify-start">
              <div v-for="creator in creators" :key="creator.id" class="pb-8 mx-auto lg:mx-0">
                <div class="flex flex-col items-center max-w-[8rem] px-3 py-4 font-medium break-words">
                  <img v-if="!creator.profile_photo_path" :src="creator.profile_photo_url"
                       class="rounded-full h-20 w-20 min-h-20 min-w-20 object-cover mb-2">
                  <img v-if="creator.profile_photo_path" :src="'/storage/' + creator.profile_photo_path"
                       class="rounded-full h-20 w-20 min-h-20 min-w-20 object-cover mb-2">
                  <span class="text-gray-200 text-center text-sm">{{ creator.name }}</span>
                </div>
              </div>
            </div>

            <!-- Paginator -->
            <!--                <Pagination :data="props.creators" class="mb-6 pb-6 border-b border-gray-800"/>-->
          </div>


          <!--                            For now, we are just displaying the team members here.
                                          This will make a good component that can be re-used across
                                          the Show and Episode Index pages. Just pass in the creators prop.

                                          We will add this when we have our Creators model setup
                                          and creators attached to the credits table for this
                                          show.                                                       -->

          <!--                            <ShowCreatorsList />-->
          <div v-if="show?.bonusContent" class="container mx-auto px-4 mb-12">
            <div class="w-full bg-gray-800 text-2xl p-4 mb-8">BONUS CONTENT</div>
          </div>


        </div>
        <div class="container mx-auto px-4 gap-y-3">
          <ShowFooter :team="team" :show="show"/>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import ShowEpisodesList from '@/Components/Pages/Shows/Elements/ShowEpisodesList'
import ShowFooter from '@/Components/Pages/Shows/Layout/ShowFooter'

const appSettingStore = useAppSettingStore()

defineProps({
  show: Object,
  team: Object,
  episodes: Object,
  creators: Object,
})
</script>