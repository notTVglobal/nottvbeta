<template>
    <div class="lg:hidden fixed top-0 w-full nav-mask">
        <div class="flex justify-between h-16 w-full border-b border-gray-100 bg-black">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <Link @click="loadStreamPage()" :href="route('stream')">
                        <JetApplicationMark class="ml-5 block h-9 w-auto"/>
                    </Link>
                </div>

                <!-- Hamburger -->
                <div class="fixed top-3 right-4 hamburgerMask">
                    <div class="-mr-2 flex items-center">
                            <div v-if="userStore.isCreator" class="text-yellow-600 uppercase hidden sm:block w-full">
                                GOAL <span class="text-xl">100</span> subscribers
                            </div>

                        <div class="mx-6">
                            <NotificationButton />
                        </div>

                        <button
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-100 transition hamburgerMask"
                            :class="{ 'hover:text-white hover:bg-blue-600': userStore.showNavDropdown}"

                            @click="userStore.toggleNavDropdown()">
            <!--                @click="chatStore.showNavDropdown = ! chatStore.showNavDropdown">-->

                            <span class="pr-2">MENU</span>
                            <svg
                                class="h-6 w-6"
                                stroke="currentColor"
                                fill="none"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    :class="{'hidden': userStore.showNavDropdown, 'inline-flex': ! userStore.showNavDropdown }"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"
                                />
                                <path
                                    :class="{'hidden': ! userStore.showNavDropdown, 'inline-flex': userStore.showNavDropdown }"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <!-- Responsive Navigation Menu -->
<!--    <div :class="{'block': showingNavigationDropdown, 'hidden': ! showingNavigationDropdown}"-->
    <div :class="{'block': userStore.showNavDropdown, 'hidden': ! userStore.showNavDropdown}"
         class="lg:hidden bg-gray-800 text-white fixed w-full h-full">
        <!-- Responsive Settings Options -->
        <!--   Fix Menu height e.g., h-[calc(h-100%-16rem)]      -->
        <div class="pb-0 h-[calc(100vh)] overflow-y-auto">
            <div class="px-4 bg-gray-800 border-b border-1 border-white w-full h-100%">

                <div class="flex justify-between pt-2">
                    <div class="flex justify-start pb-3">
                        <div v-if="$page.props.jetstream.managesProfilePhotos" class="mt-2 min-w-[2.5rem]">
                            <Link @click="userStore.closeNavDropdown()"
                                  :href="route('profile.show')"
                                  :active="route().current('profile.show')">
                                <img class="h-10 min-w-[2.5rem] rounded-full object-cover"
                                     :src="$page.props.user.profile_photo_url" :alt="$page.props.user.name">
                            </Link>
                        </div>
                        <div class="mt-1 ml-3 w-full">
                            <div class="font-medium text-base text-gray-100 w-full">
                                <Link @click="userStore.closeNavDropdown()"
                                      :href="route('profile.show')"
                                      :active="route().current('profile.show')">
                                    {{ $page.props.user.name }}
                                </Link>
                            </div>
                            <div class="font-medium text-sm text-gray-100 w-full">
                                <Link @click="userStore.closeNavDropdown()"
                                      :href="route('profile.show')"
                                      :active="route().current('profile.show')">
                                    {{ $page.props.user.email }}
                                </Link>
                            </div>
                        </div>
                    </div>
                    <div class="justify-end text-right w-12">
                        <div v-if="!userStore.isAdmin">
                            <div v-if="userStore.isSubscriber" class="text-xs font-semibold text-fuchsia-700">PREMIUM</div>
                            <div v-if="userStore.isVip" class="text-xs font-semibold text-fuchsia-700">VIP</div>
                            <div v-if="userStore.isCreator" class="text-xs font-semibold text-fuchsia-700">CREATOR</div>
                        </div>
                        <div v-if="userStore.isAdmin" class="text-xs font-semibold text-red-700">ADMIN</div>
                    </div>

                </div>
            </div>


            <div class="space-y-1 z-50 bg-gray-900 pb-20 border-b border-1 border-white">

                <JetResponsiveNavLink
                    v-if="!userStore.isSubscriber && !userStore.isAdmin && !userStore.isVip"
                    @click="userStore.closeNavDropdown()"
                    :href="route('upgrade')"
                    :active="userStore.currentPage === 'upgrade'"
                >
                    <div class="rounded-lg p-2 bg-gray-100 text-black hover:text-green-900">
                        CLICK HERE TO UPGRADE YOUR ACCOUNT
                    </div>
                </JetResponsiveNavLink>

                <JetResponsiveNavLink
                    v-if="userStore.isCreator"
                    @click="userStore.closeNavDropdown()"
                    :href="route('dashboard')"
                    :active="userStore.currentPage === 'dashboard'">
                    Dashboard
                </JetResponsiveNavLink>

                <JetResponsiveNavLink
                    v-if="userStore.isVip"
                    @click="userStore.closeNavDropdown()"
                    :href="route('library')"
                    :active="userStore.currentPage === 'library'">
                    My Library
                </JetResponsiveNavLink>

                <JetResponsiveNavLink
                    @click="userStore.closeNavDropdown()"
                    :href="route('schedule')"
                    :active="userStore.currentPage === 'schedule'">
                        Schedule
                </JetResponsiveNavLink>

                <JetResponsiveNavLink
                    @click="userStore.closeNavDropdown()"
                    :href="route('news')"
                    :active="userStore.currentPage === 'news'">
                        News
                </JetResponsiveNavLink>

                <JetResponsiveNavLink
                    v-if="userStore.isSubscriber || userStore.isVip || userStore.isCreator"
                    @click="userStore.closeNavDropdown()"
                    :href="route('movies')"
                    :active="userStore.currentPage === 'movies'">
                        Movies
                </JetResponsiveNavLink>

                <JetResponsiveNavLink
                    v-if="userStore.isSubscriber || userStore.isVip || userStore.isCreator"
                    @click="userStore.closeNavDropdown()"
                    :href="route('shows')"
                    :active="userStore.currentPage === 'shows'">
                        Shows
                </JetResponsiveNavLink>

                <JetResponsiveNavLink
                    @click="userStore.closeNavDropdown()"
                    :href="route('shop')"
                    :active="userStore.currentPage === 'shop'">
                        Shop
                </JetResponsiveNavLink>

                <JetResponsiveNavLink
                    @click="userStore.closeNavDropdown()"
                    :href="route('profile.show')"
                    :active="route().current('profile.show')">
                        Settings
                </JetResponsiveNavLink>

                <JetResponsiveNavLink
                    v-if="userStore.hasAccount"
                    @click="billingPortal">
                    Account
                </JetResponsiveNavLink>

                <JetResponsiveNavLink
                    v-if="userStore.isCreator"
                    @click="userStore.closeNavDropdown()"
                    :href="route('training')"
                    :active="userStore.currentPage === 'training'">
                    Training
                </JetResponsiveNavLink>

                <JetResponsiveNavLink
                    v-if="userStore.isCreator"
                    @click="userStore.closeNavDropdown()"
                    :href="route('videoupload')"
                    :active="userStore.currentPage === 'videoUpload'">
                    Video Upload
                </JetResponsiveNavLink>

                <!-- Authentication -->
                <form method="POST" @submit.prevent="logout">
                    <JetResponsiveNavLink as="button" class="border-t-0">
                        Log Out
                    </JetResponsiveNavLink>
                </form>



<!--                &lt;!&ndash; Creator Only Links &ndash;&gt;-->
<!--                <div v-if="userStore.isCreator">-->
<!--                    <div class="border-t border-1 mt-3 border-gray-300 bg-indigo-900 block px-4 py-2 text-xs text-white">-->
<!--                        Creator Only Links-->
<!--                    </div>-->



<!--                </div>-->

                <!-- Admin Only Links -->
                    <div v-if="userStore.isAdmin">
                        <div class="border-t border-1 mt-3 border-gray-300 bg-black block px-4 py-2 text-xs text-gray-400">
                            Admin Only Links
                        </div>

                        <JetResponsiveNavLink
                            @click="userStore.closeNavDropdown()"
                            :href="route('admin.settings')"
                            :active="route().current('admin.settings')">
                            Admin Settings
                        </JetResponsiveNavLink>

                        <JetResponsiveNavLink
                            @click="userStore.closeNavDropdown()"
                            :href="route('admin.channels')"
                            :active="route().current('admin.channels')">
                            Channels
                        </JetResponsiveNavLink>

                        <JetResponsiveNavLink
                            @click="userStore.closeNavDropdown()"
                            :href="route('calculations')"
                            :active="route().current('calculations')">
                            Calculations
                        </JetResponsiveNavLink>

                        <JetResponsiveNavLink
                            @click="userStore.closeNavDropdown()"
                            :href="route('mistServerApi')"
                            :active="route().current('mistServerApi')">
                            MistServer API
                        </JetResponsiveNavLink>
                    </div>




                <div class="flex flex-col w-full space-y-1 text-gray-600 text-sm pb-20">
                    <AppVersion />
                </div>
            </div>
        </div> <div class="fixed w-full bottom-4 text-center hidden">Scroll the menu.</div>
    </div>
    </div>
</template>

<script setup>
import JetResponsiveNavLink from '@/Jetstream/ResponsiveNavLink.vue'
import NotificationsButton from '@/Components/Navigation/NotificationsButton.vue'
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useChatStore } from "@/Stores/ChatStore"
import { useUserStore } from "@/Stores/UserStore"
import { ref } from "vue"
import {Inertia} from "@inertiajs/inertia"
import JetApplicationMark from "@/Jetstream/ApplicationMark.vue";
import {Link} from "@inertiajs/inertia-vue3";
import AppVersion from "@/Components/AppVersion/AppVersion.vue";
import NotificationButton from "@/Components/Notifications/NotificationButton.vue";
const showingNavigationDropdown = ref(false);

let videoPlayerStore = useVideoPlayerStore();
let chatStore = useChatStore();
let userStore = useUserStore();

let props = defineProps({
    user: Object,
})

const logout = () => {
    Inertia.post(route('logout'));
    videoPlayerStore.mute();
    videoPlayerStore.fullPage = true;
    videoPlayerStore.loggedIn = false;
    videoPlayerStore.ottChat = false;
    videoPlayerStore.ott = 0;
    videoPlayerStore.class = "welcomeVideoClass";
    videoPlayerStore.videoContainerClass = "welcomeVideoContainer";
    userStore.showNavDropdown = false;
    userStore.clearUserData()
};

function loadStreamPage() {
    videoPlayerStore.makeVideoFullPage()
    videoPlayerStore.ott = 0
    userStore.showNavDropdown = false
}

function billingPortal() {
    location.href = ('https://not.tv/billing-portal')
}

function openNotifications() {
    document.getElementById('my_modal_3').showModal()
}

</script>
