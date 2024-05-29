<template>
    <Head title="Settings" />

    <div class="place-self-center flex flex-col gap-y-3">
        <div id="topDiv" class="bg-dark text-light p-5 mb-10">

            <header class="flex justify-between mb-3">
                <div id="topDiv">
                    <h2 class="font-semibold text-4xl text-gray-200 text-center leading-tight">
                        Settings
                    </h2>
                </div>
            </header>



            <!--        </template>-->
            <div class="place-self-center flex flex-col gap-y-3">
                <div class="text-black p-5 mb-10">

                    <div class="text-black">
                        <div class="max-w-7xl mx-auto pb-10 sm:px-6 lg:px-8">

                            <div v-if="$page.props.jetstream.canUpdateProfileInformation">
                                <UpdateProfileInformationForm :user="$page.props.auth.user" />

                                <JetSectionBorder />
                            </div>

                            <div v-if="$page.props.jetstream.canUpdateProfileInformation">
                                <UserUpdateContactForm :user="$page.props.auth.user" class="pt-10"/>

                                <JetSectionBorder />
                            </div>


                            <div v-if="$page.props.jetstream.canUpdatePassword">
                                <UpdatePasswordForm class="mt-10 sm:mt-0" />

                                <JetSectionBorder />
                            </div>


                            <div v-if="$page.props.jetstream.canManageTwoFactorAuthentication">
                                <TwoFactorAuthenticationForm
                                    :requires-confirmation="confirmsTwoFactorAuthentication"
                                    class="mt-10 sm:mt-0"
                                />

                                <JetSectionBorder />
                            </div>


<!--                          <LogoutOtherBrowserSessionsForm :sessions="sessions" class="mt-10 sm:mt-0"/>-->


                            <template v-if="$page.props.jetstream.hasAccountDeletionFeatures">
                                <JetSectionBorder />

                                <DeleteUserForm class="mt-10 sm:mt-0" />
                            </template>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>



</template>

<script setup>
import { usePageSetup } from '@/Utilities/PageSetup'
import DeleteUserForm from '@/Pages/Profile/Partials/DeleteUserForm'
import JetSectionBorder from '@/Jetstream/SectionBorder.vue'
import LogoutOtherBrowserSessionsForm from '@/Pages/Profile/Partials/LogoutOtherBrowserSessionsForm'
import TwoFactorAuthenticationForm from '@/Pages/Profile/Partials/TwoFactorAuthenticationForm'
import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm'
import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm'
import UserUpdateContactForm from "@/Components/Pages/Users/UserUpdateContactForm"

usePageSetup('settings')

defineProps({
    // can: Object,
    confirmsTwoFactorAuthentication: Boolean,
    sessions: Array,
});


</script>

