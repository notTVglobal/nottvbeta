<template>
    <Head title="Settings" />

    <div id="topDiv"></div>
    <div class="place-self-center flex flex-col gap-y-3">
        <div class="bg-dark text-light p-5 mb-10">

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
                                <UpdateProfileInformationForm :user="$page.props.user" />

                                <JetSectionBorder />
                            </div>

                            <div v-if="$page.props.jetstream.canUpdateProfileInformation">
                                <UserUpdateContactForm :user="$page.props.user" class="pt-10"/>

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


<!--                            <LogoutOtherBrowserSessionsForm :sessions="sessions" class="mt-10 sm:mt-0" />-->

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
import { onBeforeMount, onMounted, ref } from "vue"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useUserStore } from "@/Stores/UserStore"
import DeleteUserForm from '@/Pages/Profile/Partials/DeleteUserForm.vue'
import JetSectionBorder from '@/Jetstream/SectionBorder.vue'
import LogoutOtherBrowserSessionsForm from '@/Pages/Profile/Partials/LogoutOtherBrowserSessionsForm.vue'
import TwoFactorAuthenticationForm from '@/Pages/Profile/Partials/TwoFactorAuthenticationForm.vue'
import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm.vue'
import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm.vue'
import UserUpdateContactForm from "@/Components/Users/UserUpdateContactForm.vue"

// import {vue} from "laravel-mix";

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()

videoPlayerStore.currentPage = 'settings'

onBeforeMount(() => {
    userStore.scrollToTopCounter = 0;
})

onMounted(() => {
    videoPlayerStore.makeVideoTopRight()
    if (userStore.scrollToTopCounter === 0 ) {
        document.getElementById("topDiv").scrollIntoView()
        userStore.scrollToTopCounter ++;
    }
});

// let props = defineProps({
//     can: Object,
// })

defineProps({
    // can: Object,
    confirmsTwoFactorAuthentication: Boolean,
    sessions: Array,
});


</script>

