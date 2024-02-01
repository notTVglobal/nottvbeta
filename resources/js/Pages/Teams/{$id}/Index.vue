<template>

  <Head :title="props.team.name"/>

  <!--    <div class="place-self-center flex flex-col gap-y-3 overflow-x-hidden">-->
  <div class="place-self-center flex flex-col">
    <!--        <div id="topDiv" class="light:bg-white light:text-black dark:bg-gray-800 dark:text-gray-50 rounded p-5 mb-10">-->
    <div id="topDiv" class="bg-gray-800 text-gray-50 rounded px-5 pt-6">

      <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>

      <header class="flex justify-between mb-3 pt-6">
        <div>
          <h3 class="light:text-gray-900 dark:text-gray-50 inline-flex items-center text-3xl font-semibold relative uppercase">

            <SingleImage :image="image" :alt="'team logo'" :class="'w-20 mr-2'"/>
            {{ props.team.name }}

          </h3>

        </div>
        <div class="flex flex-wrap-reverse justify-end gap-2">
          <div>
            <button
                v-if="props.can.manageTeam"
                @click="appSettingStore.btnRedirect(`/teams/${props.team.slug}/manage`)"
                class="px-4 py-2 text-white bg-orange-600 hover:bg-orange-500 rounded-lg"
            >Manage Team
            </button>
          </div>
          <div>
            <button
                v-if="props.can.editTeam"
                @click="appSettingStore.btnRedirect(`/teams/${props.team.slug}/edit`)"
                class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
            >Edit
            </button>
          </div>
          <div>
            <button
                v-if="props.user.role_id === 4"
                @click="appSettingStore.btnRedirect(`/dashboard`)"
                class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                hidden
            >Dashboard
            </button>
          </div>
        </div>
      </header>

      <p class="description mb-6 p-5">
        {{ props.team.description }}
      </p>

      <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
          <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">

            <div class="w-full bg-gray-900 text-white text-2xl p-4 mb-8">SHOWS</div>

            <TeamShowsList :shows="props.shows"/>

            <div class="w-full bg-gray-900 text-white text-2xl p-4 mb-8">CREATORS</div>

            <div class="flex flex-row flex-wrap">
              <div v-for="creator in props.creators.data"
                   :key="creator.id"
                   class="pb-8 bg-gray-800">

                <div class="flex flex-col min-w-[8rem] px-6 py-4 font-medium break-words grow-0">
                  <img :src="'/storage/' + creator.profile_photo_path"
                       class="pb-2 rounded-full h-32 w-32 object-cover mb-2">
                  <span class="text-gray-50 w-full text-center">{{ creator.name }}</span>
                </div>
              </div>
            </div>

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

usePageSetup('teams/slug')

const appSettingStore = useAppSettingStore()
const teamStore = useTeamStore()

let props = defineProps({
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

</script>

<style scoped>
.description {
  white-space: pre-wrap; /* CSS property to preserve whitespace and wrap text */
  @apply tracking-wide
}
</style>
