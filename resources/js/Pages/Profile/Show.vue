<template>
  <Head title="Settings"/>
  <!--    <AppLayout>-->
  <div id="topDiv" class="mt-10 z-10">

    <!--        <template #header>-->
    <h2 class="font-semibold text-4xl text-gray-200 text-center leading-tight">
      Settings
    </h2>
    <!--        </template>-->
    <div class="place-self-center flex flex-col gap-y-3">
      <div class="text-black p-5 mb-10">

        <div class="text-black">
          <div class="max-w-7xl mx-auto pb-10 sm:px-6 lg:px-8">
            <div v-if="$page.props.jetstream.canUpdateProfileInformation">
              <UpdateProfileInformationForm :user="$page.props.auth.user"/>

              <JetSectionBorder/>
            </div>
            <div v-if="$page.props.jetstream.canUpdatePassword">
              <UpdatePasswordForm class="mt-10 sm:mt-0"/>

              <JetSectionBorder/>
            </div>

            <div v-if="$page.props.user.isCreator">
              <UpdateCreatorSettings class="pt-10" />

              <JetSectionBorder/>
            </div>

            <div v-if="$page.props.user.newsPersonId">
              <UpdateNewsPersonSettings class="pt-10" />

              <JetSectionBorder/>
            </div>



            <div>
              <UpdateContactInformationForm class="pt-10"/>

              <JetSectionBorder/>
            </div>

            <div v-if="$page.props.jetstream.canManageTwoFactorAuthentication">
              <TwoFactorAuthenticationForm
                  :requires-confirmation="confirmsTwoFactorAuthentication"
                  class="mt-10 sm:mt-0"
              />

              <JetSectionBorder/>
            </div>

            <LogoutOtherBrowserSessionsForm :sessions="sessions" class="mt-10 sm:mt-0"/>

            <template v-if="$page.props.jetstream.hasAccountDeletionFeatures">
              <JetSectionBorder/>

              <DeleteUserForm class="mt-10 sm:mt-0"/>
            </template>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--    </AppLayout>-->
</template>

<script setup>
import { usePageSetup } from '@/Utilities/PageSetup'
import { useUserStore } from "@/Stores/UserStore"
import DeleteUserForm from '@/Pages/Profile/Partials/DeleteUserForm'
import JetSectionBorder from '@/Jetstream/SectionBorder'
import LogoutOtherBrowserSessionsForm from '@/Pages/Profile/Partials/LogoutOtherBrowserSessionsForm'
import TwoFactorAuthenticationForm from '@/Pages/Profile/Partials/TwoFactorAuthenticationForm'
import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm'
import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm'
import UpdateContactInformationForm from '@/Pages/Profile/Partials/UpdateContactInformationForm'
import UpdateCreatorSettings from '@/Pages/Profile/Partials/UpdateCreatorSettings.vue'
import UpdateNewsPersonSettings from '@/Pages/Profile/Partials/UpdateNewsPersonSettings.vue'

const userStore = useUserStore()

usePageSetup('/user/profile')

defineProps({
  confirmsTwoFactorAuthentication: Boolean,
  sessions: Array,
});
</script>


