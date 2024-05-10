<template>
  <div>
    <div class="container mx-auto px-4 gap-y-3 rounded sm:rounded-lg shadow">
      <div class="show-details border-b border-gray-800 pb-12 flex flex-col xl:flex-row">
        <div class="items-center relative">
          <!--                        <SingleImage :image="props.show.image" :poster="props.show.poster" :alt="'show cover'" class="h-96 min-w-[16rem] w-64 object-cover mb-6 lg:mb-0 m-auto lg:m-0"/>-->
          <div v-if="show.status.id === 9" class="absolute flex justify-end w-full -mt-3 z-50">
            <CreatorsOnlyBadge/>
          </div>
<!--          <SingleImage :image="props.show.image" :alt="'show cover'"-->
<!--                       class="max-h-96 min-w-[16rem] max-w-64 object-cover mb-6 xl:mb-0 m-auto xl:m-0"/>-->
          <SingleImageWithModal :image="props.show.image" :alt="'show cover'"
                                class="max-h-96 min-w-[16rem] max-w-64 object-cover mb-6 xl:mb-0 m-auto xl:m-0 transition-transform duration-300 ease-in-out transform hover:scale-105"/>

        </div>
        <div class="flex flex-col xl:ml-12 xl:mr-0 w-full justify-center items-center xl:items-start xl:justify-start">
          <div @click="Inertia.visit(`/teams/${team.slug}`)"
               class="text-center xl:text-left text-blue-500 hover:text-blue-400 transition hover:cursor-pointer tracking-wide">
            {{ team.name }}
          </div>
          <h2 class="font-semibold text-4xl text-center lg:text-left">{{ show.name }}</h2>
          <div class="text-gray-400 text-center lg:text-left">
            <div class="mt-1">
              <span class="uppercase tracking-wider text-yellow-700">{{ show?.category?.name }}</span>
              &nbsp;&middot;&nbsp;
              <span class="tracking-wide text-yellow-500">{{ show?.subCategory?.name }}</span>
              <span v-if="show.last_release_year"> &nbsp;&middot;&nbsp; {{
                  show.first_release_year
                }}-{{ show.last_release_year }}</span>
              <span v-if="!show.last_release_year && show.first_release_year"> &nbsp;&middot;&nbsp; {{
                  show.first_release_year
                }}</span>
              <span v-if="!show.last_release_year && !show.first_release_year"> &nbsp;&middot;&nbsp; {{
                  show.copyrightYear
                }}</span>
            </div>
            <div class="flex flex-row">

            </div>

            <!--                                This will need a change in the database to allow the creator to update
                                                the year(s) if they are incorrect. It can automatically set the first release year
                                                to the same year that the show is created. And the final release year to the year that
                                                the show was last updated (created_at and updated_at timestamps) -->
            <!--                                <span>{{show.copyrightYear}}-{{new Date().getFullYear()}}</span>-->

          </div>

          <div class="">
          <SocialMediaBadgeLinks :socialMediaLinks="show.socialMediaLinks"/>
          </div>


          <div class="w-full flex flex-wrap mt-5 m-auto xl:mx-0 justify-center xl:justify-start">
            <div class="flex flex-wrap gap-x-4 gap-y-2 ">

              <ShowIdIndexPlayEpisode :show="show" :team="team"/>

              <ComingSoonShareAndSaveButtons/>

            </div>
          </div>


          <div class="description mt-2 w-full text-gray-300 text-center xl:text-left">
            <expandable-description :description="show.description" :hideTitle="true"/>
          </div>


        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { Inertia } from '@inertiajs/inertia'
import CreatorsOnlyBadge from '@/Components/Global/Badges/CreatorsOnlyBadge.vue'
import ComingSoonShareAndSaveButtons from '@/Components/Global/UserActions/ComingSoonShareAndSaveButtons.vue'
import SocialMediaBadgeLinks from '@/Components/Global/Badges/SocialMediaBadgeLinks.vue'
import ExpandableDescription from '@/Components/Global/Text/ExpandableDescription.vue'
import SingleImageWithModal from '@/Components/Global/Multimedia/SingleImageWithModal.vue'
import ShowIdIndexPlayEpisode from '@/Components/Pages/Shows/Elements/ShowIdIndexPlayEpisode.vue'

const props = defineProps({
  show: Object,
  team: Object,
})

</script>
