<template>

  <Head title="Creator Invite Introduction"/>
  <div class="bg-gray-900 h-[calc(100vh)]">
    <div>
      <PublicNavigationMenu class="fixed top-0 w-full h-16"/>
      <PublicResponsiveNavigationMenu/>
    </div>

    <div id="topDiv" class="bg-gray-900 text-white px-5 flex flex-col w-full hide-scrollbar">

      <div
          class="flex flex-col mt-36 mb-16 text-gray-50 justify-center w-full text-center text-3xl font-semibold tracking-widest">
        <div>
          <ApplicationLogo class="mx-auto w-1/2 lg:w-1/4 xl:w-1/6 mb-6"/>
        </div>
        <div class="uppercase">
          Welcome to Your Next Adventure in Creativity!
        </div>
      </div>
      <main class="pb-8 hide-scrollbar">

        <div v-if="currentState === 'form'" class="flex justify-center w-full">
          <form @submit.prevent="checkInviteCode" class="flex justify-center gap-2">
            <div><input v-model="inviteCodeInput" type="text" placeholder="Enter your invite code"
                        class="input input-bordered w-full max-w-xs text-black"/></div>
            <div class="w-fit mx-auto">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
          </form>
        </div>

        <transition name="fade">
          <WelcomeComponent v-if="currentState === 'welcome'"/>
        </transition>

        <!-- Assuming ContentComponent exists and is imported -->
        <transition name="fade">
          <ContentComponent v-if="currentState === 'content'" :inviteCodeUlid="inviteCodeUlid"/>
        </transition>

      </main>
      <PopUpModal :id="`checkInviteCodeFailedModal`">
        <template #header>{{ checkInviteCodeFailedModalHeader }}</template>
        <template #main>{{ checkInviteCodeFailedModalMain }}</template>
      </PopUpModal>

      <Footer/>

    </div>
  </div>
</template>

<script setup>
import { Inertia } from '@inertiajs/inertia'
import { onMounted, ref } from 'vue'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import ApplicationLogo from '@/Jetstream/ApplicationLogo'
import PublicNavigationMenu from '@/Components/Global/Navigation/PublicNavigationMenu'
import PublicResponsiveNavigationMenu from '@/Components/Global/Navigation/PublicResponsiveNavigationMenu'
import Footer from '@/Components/Global/Layout/Footer'
import WelcomeComponent from '@/Components/Pages/CreatorsInvite/CreatorsInviteWelcomeComponent'
import ContentComponent from '@/Components/Pages/CreatorsInvite/CreatorsInviteContentComponent'
import PopUpModal from '@/Components/Global/Modals/PopUpModal.vue'

const appSettingStore = useAppSettingStore()

appSettingStore.noLayout = true
appSettingStore.currentPage = 'creator.invite.show'

const props = defineProps({
  inviteCodeUlid: String,
})

const inviteCodeInput = ref('')
const currentState = ref('form') // Possible values: 'form', 'welcome', 'content'
const checkInviteCodeFailedModalHeader = ref('')
const checkInviteCodeFailedModalMain = ref('')

const checkInviteCode = () => {
  Inertia.post(`/invite/${props.inviteCodeUlid}/check-invite-code`, {inviteCodeInput: inviteCodeInput.value}, {
    onSuccess: () => {
      currentState.value = 'welcome'
      setTimeout(() => currentState.value = 'content', 500) // Adjust timing as needed
    },
    onError: (error) => {
      checkInviteCodeFailedModalHeader.value = 'Please Try Again'
      checkInviteCodeFailedModalMain.value = Object.values(error)[0]
      document.getElementById('checkInviteCodeFailedModal').showModal()
    },
  })


//
//   Inertia.post(`/invite/${props.inviteCodeUri}/check-invite-code`, inviteCodeInput.value).onSuccess({
//     if(inviteCodeInput.value === actualInviteCode)
//   {
//     currentState.value = 'welcome';
//     setTimeout(() => currentState.value = 'content', 500); // Adjust timing as needed
//   }
// else
//   {
//     checkInviteCodeFailedModalHeader.value = 'Incorrect Code'
//     checkInviteCodeFailedModalMain.value = 'Please try again.'
//     document.getElementById('checkInviteCodeFailedModal').showModal()
//   }
// })

}


onMounted(() => {
  const topDiv = document.getElementById('topDiv')
  topDiv.scrollIntoView()
})
</script>
