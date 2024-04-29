<template>

  <Head :title="props.team.name"/>

  <!--    <div class="place-self-center flex flex-col gap-y-3 overflow-x-hidden">-->
  <div id="topDiv" class="place-self-center h-screen flex flex-col">
    <!--        <div id="topDiv" class="light:bg-white light:text-black dark:bg-gray-800 dark:text-gray-50 rounded p-5 mb-10">-->
    <div
        class="min-h-screen w-full bg-gray-800 text-gray-50 dark:bg-gray-800 dark:text-gray-50 rounded sm:rounded-lg shadow pt-4">


    <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>
      <TeamIdIndexHeader :team="team" :image="image"/>
      <div class="flex justify-between mb-1 pt-6">

        <div class="flex flex-col px-5 items-start">
          <div class="flex flex-wrap-reverse justify-end gap-2 mb-2">
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
          <div class="flex flex-wrap-reverse justify-end gap-2">
            <button
                v-if="props?.can?.editTeam"
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
      </div>

      <TeamIdIndexBanner :team="team" :image="image" />

      <!--      <p v-if="props.team.description" class="description mb-6 p-5">-->
      <!--        {{ props.team.description }}-->
      <!--      </p>-->


      <div class="grid grid-cols-1 lg:grid-cols-2">
        <div class="order-last lg:order-none">
          <TeamIdIndexDescription :team="team"/>
        </div>
        <div class="px-5">

            <SearchShowEpisodesComponent :modelType="`team`" :modelId="props.team.id" :modelSlug="props.team.slug" :shows="shows">
              <!-- Provide custom title -->
              <template v-slot:title>
                <h2 class="text-white text-lg font-semibold mb-2">Advanced Episode Search</h2>
              </template>
              <!-- Provide custom description -->
              <template v-slot:description>
                <p class="text-gray-400 text-sm mb-4">Search through all of our shows to find episodes.</p>
              </template>
            </SearchShowEpisodesComponent>

            <TeamShowsList :shows="props?.shows"/>
        </div>

      </div>

      <div class="flex flex-col">
        <div class="overflow-x-auto">
          <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
            <TeamIdIndexCreators :creators="creators" v-if="showCreators" />




            <!--                            For now, we are just displaying the team members here.
                                            This will make a good component that can be re-used across
                                            the Show and Episode Index pages. Just pass in the creators prop.

                                            We will add this when we have our Creators model setup
                                            and creators attached to the credits table for this
                                            show.                                                       -->

            <!--                            <ShowCreatorsList />-->

            <!--  <TeamFooter />  -->

          </div>
        </div>
      </div>

    </div>
  </div>

</template>


<script setup>
import { usePageSetup } from '@/Utilities/PageSetup'
import { useTeamStore } from '@/Stores/TeamStore'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import TeamShowsList from '@/Components/Pages/Teams/Elements/TeamShowsList'
import Message from '@/Components/Global/Modals/Messages'
import SingleImage from '@/Components/Global/Multimedia/SingleImage'
import SocialMediaBadgeLinks from '@/Components/Global/Badges/SocialMediaBadgeLinks.vue'
import SearchShowEpisodesComponent from '@/Components/Global/Search/SearchShowEpisodesComponent.vue'
import { computed, ref } from 'vue'
import TeamIdIndexBanner from '@/Components/Pages/Teams/Elements/TeamIdIndexBanner.vue'
import TeamIdIndexCreators from '@/Components/Pages/Teams/Elements/TeamIdIndexCreators.vue'
import TeamIdIndexDescription from '@/Components/Pages/Teams/Elements/TeamIdIndexDescription.vue'
import TeamIdIndexHeader from '@/Components/Pages/Teams/Elements/TeamIdIndexHeader.vue'

usePageSetup('teams/slug')

const appSettingStore = useAppSettingStore()
const teamStore = useTeamStore()

const props = defineProps({
  user: Object,
  team: Object,
  logo: String,
  image: Object,
  shows: Object,
  creators: Object,
  filters: Object,
  can: Object,
})

teamStore.setActiveTeam(props.team)
teamStore.can = props.can

const showCreators = false

const showFullDescription = ref(false)

const truncatedDescription = computed(() => {
  if (props.team.description.length <= 300 || showFullDescription) {
    return props.team.description
  } else {
    return props.team.description.slice(0, 300)
  }
})


</script>

<style scoped>
.description {
  overflow: hidden;
  white-space: pre-wrap; /* CSS property to preserve whitespace and wrap text */
  text-overflow: ellipsis;
  @apply tracking-wide
}
</style>
