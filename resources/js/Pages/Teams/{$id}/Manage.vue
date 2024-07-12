<template>
  <Head :title="`Manage Team: ${team.name}`"/>

  <div class="place-self-center flex flex-col gap-y-3 w-full overscroll-x-none">
    <div id="topDiv" class="min-h-screen bg-white text-black dark:bg-gray-900 dark:text-gray-50 p-5 pb-36">

      <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>

      <header
          class="mb-3 bg-gradient-to-r from-orange-200 via-orange-100 to-transparent p-4 text-black font-bold rounded-lg">
        <!--            <header class="pulsing-background mb-3 p-4 text-white font-bold rounded-lg">-->

        <div class="flex justify-between mb-3 pt-6">
          <div class="font-bold mb-4 text-black align-bottom text-lg">MANAGE TEAM</div>
          <div class="flex flex-wrap-reverse justify-end gap-2">

            <button
                @click="appSettingStore.btnRedirect(`/teams/${team.slug}`)"
                class="px-4 py-2 h-fit text-white font-semibold bg-blue-500 hover:bg-blue-600 rounded-lg"
            >
              <font-awesome-icon :icon="['fas', 'users']" class="hover:text-blue-800 mr-1" /> View Team
            </button>

            <button
                v-if="can.editTeam"
                @click="appSettingStore.btnRedirect(`/teams/${team.slug}/edit`)"
                class="px-4 py-2 h-fit text-white font-semibold bg-blue-500 hover:bg-blue-600 rounded-lg"
            >
              <font-awesome-icon :icon="['fas', 'pen']" class="hover:text-blue-800 mr-1" /> Edit Team
            </button>

            <DashboardButton/>

          </div>
        </div>

        <TeamManageHeader/>

      </header>

      <div class="flex flex-col max-w-calc[100%-96rem]">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
          <div class="py-2 align-middle inline-block w-full sm:px-6 lg:px-8 space-y-4">

            <div @click="toggleComponent('teamShows')"
                 :class="{'rounded-t-lg': teamStore.openComponent === 'teamShows', 'rounded-lg': teamStore.openComponent !== 'teamShows'}"
                 class="accordion-header p-2 font-bold transition duration-300 ease-in-out transform focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 overflow-hidden shadow-lg bg-orange-300 hover:bg-blue-100 dark:hover:bg-blue-900 text-black hover:text-blue-900 dark:text-blue-100 dark:hover:text-white">
              <font-awesome-icon icon="fa-clapperboard" class=""/>
              Shows
            </div>
            <div v-if="teamStore.openComponent === 'teamShows'">
              <div class="mt-4 mb-12 pb-6 shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                <TeamShowsList :shows="shows" :can="can"/>
              </div>
            </div>

            <div v-if="can.hasSpecialPermission" @click="toggleComponent('teamMembers')"
                 :class="{'rounded-t-lg': teamStore.openComponent === 'teamMembers', 'rounded-lg': teamStore.openComponent !== 'teamMembers'}"
                 class="accordion-header p-2 font-bold transition duration-300 ease-in-out transform focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 overflow-hidden shadow-lg bg-orange-300 hover:bg-blue-100 dark:hover:bg-blue-900 text-black hover:text-blue-900 dark:text-blue-100 dark:hover:text-white">
              <font-awesome-icon icon="fa-user-group" class=""/>
              Team Members
            </div>
            <div v-if="teamStore.openComponent === 'teamMembers'">
              <div class="mt-4 mb-12 pb-6 shadow overflow-hidden sm:rounded-lg">
                <TeamMembersList/>
              </div>
            </div>

            <div @click="toggleComponent('teamAssignments')"
                 :class="{'rounded-t-lg': teamStore.openComponent === 'teamAssignments', 'rounded-lg': teamStore.openComponent !== 'teamAssignments'}"
                 class="accordion-header p-2 font-bold transition duration-300 ease-in-out transform focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 overflow-hidden shadow-lg bg-orange-300 hover:bg-blue-100 dark:hover:bg-blue-900 text-black hover:text-blue-900 dark:text-blue-100 dark:hover:text-white">
              <font-awesome-icon icon="fa-clipboard-list" class=""/>
              Team Assignments
            </div>
            <div v-if="teamStore.openComponent === 'teamAssignments'">
              <div class="mt-4 pb-6 shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                <TeamAssignmentsList/>
              </div>
            </div>

            <div v-if="can.transferTeam">
              <div @click="toggleComponent('teamTransfer')"
                   :class="{'rounded-t-lg': teamStore.openComponent === 'teamTransfer', 'rounded-lg': teamStore.openComponent !== 'teamTransfer'}"
                   class="accordion-header p-2 font-bold transition duration-300 ease-in-out transform focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 overflow-hidden shadow-lg bg-orange-300 hover:bg-blue-100 dark:hover:bg-blue-900 text-black hover:text-blue-900 dark:text-blue-100 dark:hover:text-white">
                <font-awesome-icon icon="fa-arrow-right-arrow-left" class=""/>
                Team Transfer Process
              </div>
              <div v-if="teamStore.openComponent === 'teamTransfer'">
                <div class="mt-4 pb-6 shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                  <TeamTransferProcess :can="can" :teamStatusId="team.team_status_id"/>
                </div>
              </div>
            </div>
            <!--  <TeamFooter />  -->

          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { onBeforeUnmount, onMounted } from 'vue'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useTeamStore } from '@/Stores/TeamStore'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import TeamManageHeader from '@/Components/Pages/Teams/Manage/Layout/TeamManageHeader'
import TeamMembersList from '@/Components/Pages/Teams/Manage/Elements/TeamMembersList'
import TeamShowsList from '@/Components/Pages/Teams/Manage/Elements/TeamShowsList'
import TeamAssignmentsList from '@/Components/Pages/Teams/Manage/Elements/TeamAssignmentsList'
import Message from '@/Components/Global/Modals/Messages'
import TeamTransferProcess from '@/Components/Pages/Teams/Manage/Elements/TeamTransferProcess'
import DashboardButton from '@/Components/Global/Buttons/DashboardButton.vue'

usePageSetup('teams/slug/manage')

const appSettingStore = useAppSettingStore()
const teamStore = useTeamStore()

// Map store state to local computed properties
// const team = computed(() => teamStore.team || {})
// const can = computed(() => teamStore.can)

const toggleComponent = (componentName) => {
  teamStore.openComponent = teamStore.openComponent === componentName ? null : componentName
}

const savedPosition = sessionStorage.getItem('scrollPosition')
if (savedPosition) {
  window.scrollTo(0, parseInt(savedPosition))
  sessionStorage.removeItem('scrollPosition') // Clear after restoring
}

let props = defineProps({
  team: Object,
  shows: Object,
  contributors: Object,
  filters: Object,
  can: Object,
})

console.log('Props received:', props)
// teamStore.setActiveTeam(props.team)

onMounted(() => {
  teamStore.initializeTeam({...props.team})
  teamStore.initializeShows({...props.shows})
  teamStore.initializeContributors({...props.contributors})
  teamStore.setCan({...props.can})
})

onBeforeUnmount(() => {

})

</script>

<style scoped>
@keyframes pulse-bg {
  0%, 100% {
    background-color: rgba(255, 237, 213, 1); /* Fully opaque */
  }
  50% {
    background-color: rgba(255, 237, 213, 0.8); /* Slightly transparent */
  }
}

.pulsing-background {
  animation: pulse-bg 2s infinite ease-in-out;
  background-color: rgba(255, 237, 213, 1); /* Starting color */
}

</style>
