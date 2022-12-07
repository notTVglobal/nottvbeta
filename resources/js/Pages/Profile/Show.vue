<template>
    <Head title="Settings" />
<!--    <AppLayout>-->
    <div class="overscroll-y-none">

<!--        <template #header>-->
            <h2 id="topDiv" class="font-semibold text-4xl text-gray-200 text-center leading-tight">
                Profile
            </h2>
<!--        </template>-->
        <div class="place-self-center flex flex-col gap-y-3 overflow-y-scroll">
            <div class="text-black p-5 mb-10">

        <div class="text-black">
            <div class="max-w-7xl mx-auto pb-10 sm:px-6 lg:px-8">
                <div v-if="$page.props.jetstream.canUpdateProfileInformation">
                    <UpdateProfileInformationForm :user="$page.props.user" />

                    <JetSectionBorder />

                    <UpdateContactInformationForm :user="$page.props.user" class="pt-10"/>

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

                <LogoutOtherBrowserSessionsForm :sessions="sessions" class="mt-10 sm:mt-0" />

                <template v-if="$page.props.jetstream.hasAccountDeletionFeatures">
                    <JetSectionBorder />

                    <DeleteUserForm class="mt-10 sm:mt-0" />
                </template>
            </div>
        </div>
            </div>
        </div>
    </div>
<!--    </AppLayout>-->
</template>

<script setup>
import DeleteUserForm from '@/Pages/Profile/Partials/DeleteUserForm.vue'
import JetSectionBorder from '@/Jetstream/SectionBorder.vue'
import LogoutOtherBrowserSessionsForm from '@/Pages/Profile/Partials/LogoutOtherBrowserSessionsForm.vue'
import TwoFactorAuthenticationForm from '@/Pages/Profile/Partials/TwoFactorAuthenticationForm.vue'
import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm.vue'
import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm.vue'
import UpdateContactInformationForm from '@/Pages/Profile/Partials/UpdateContactInformationForm.vue'
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useChatStore } from "@/Stores/ChatStore.js"
import {onMounted} from "vue";

let videoPlayerStore = useVideoPlayerStore()
let chat = useChatStore()

videoPlayerStore.currentPage = 'profile'

onMounted(() => {
    videoPlayerStore.makeVideoTopRight();
    document.getElementById("topDiv").scrollIntoView()
});

defineProps({
    confirmsTwoFactorAuthentication: Boolean,
    sessions: Array,
});
</script>


