<template>
    <!-- Hamburger -->
    <div class="absolute top-3 right-4 hamburgerMask">
        <div class="-mr-2 flex items-center lg:hidden z-50">
                <div v-if="$page.props.user.role_id === 4" class="text-yellow-600 uppercase hidden sm:block w-full">
                    GOAL <span class="text-xl">100</span> subscribers
                </div>

                <div class="-mt-16 mr-12">
                    <NotificationsButton class=""/>
                </div>
            <button
                class="inline-flex items-center justify-center p-2 rounded-md text-gray-100 transition"
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
    <!-- Responsive Navigation Menu -->
<!--    <div :class="{'block': showingNavigationDropdown, 'hidden': ! showingNavigationDropdown}"-->
    <div :class="{'block': userStore.showNavDropdown, 'hidden': ! userStore.showNavDropdown}"
         class="lg:hidden bg-gray-800 text-white fixed w-full h-full">
        <!-- Responsive Settings Options -->
        <!--   Fix Menu height e.g., h-[calc(h-100%-16rem)]      -->
        <div class="pt-16 pb-0 h-[calc(100vh)] overflow-y-auto">
            <div class="px-4 bg-gray-800 border-b border-1 border-white w-full h-100%">

                <div class="flex justify-between pt-2">
                    <div class="flex justify-start pb-3">
                        <div v-if="$page.props.jetstream.managesProfilePhotos" class="mt-2 min-w-[2.5rem]">
                            <Link @click="videoPlayer.makeVideoTopRight()"
                                  :href="route('profile.show')"
                                  :active="route().current('profile.show')">
                                <img class="h-10 min-w-[2.5rem] rounded-full object-cover"
                                     :src="$page.props.user.profile_photo_url" :alt="$page.props.user.name">
                            </Link>
                        </div>
                        <div class="mt-1 ml-3 w-full">
                            <div class="font-medium text-base text-gray-100 w-full">
                                <Link @click="videoPlayer.makeVideoTopRight()"
                                      :href="route('profile.show')"
                                      :active="route().current('profile.show')">
                                    {{ $page.props.user.name }}
                                </Link>
                            </div>
                            <div class="font-medium text-sm text-gray-100 w-full">
                                <Link @click="videoPlayer.makeVideoTopRight()"
                                      :href="route('profile.show')"
                                      :active="route().current('profile.show')">
                                    {{ $page.props.user.email }}
                                </Link>
                            </div>
                        </div>
                    </div>
                    <div class="justify-end text-right w-12">
                        <div v-if="$page.props.user.role_id === 2" class="text-xs font-semibold text-fuchsia-700">PREMIUM SUBSCRIBER</div>
                        <div v-if="$page.props.user.role_id === 3" class="text-xs font-semibold text-fuchsia-700">VIP</div>
                        <div v-if="$page.props.user.role_id === 4" class="text-xs font-semibold text-fuchsia-700">CREATOR</div>
                        <div v-if="$page.props.user.isAdmin === 1" class="text-xs font-semibold text-red-700">ADMIN</div>
                        <div v-if="$page.props.user.role_id === 1" class="align-self-end text-lg font-semibold text-fuchsia-700 hover:text-fuchsia-500">
                            <Link @click="videoPlayer.makeVideoTopRight()" :href="route('upgrade')" :active="route().current('upgrade')">
                                CLICK HERE TO UPGRADE YOUR ACCOUNT
                            </Link>
                        </div>
                    </div>

                </div>
            </div>


            <div class="space-y-1 z-50 bg-gray-900 pb-20 border-b border-1 border-white">

                <JetResponsiveNavLink
                    v-if="$page.props.user.role_id === 4"
                    @click="userStore.closeNavDropdown()"
                    :href="route('dashboard')"
                    :active="route().current('dashboard')">
                    Dashboard
                </JetResponsiveNavLink>

                <JetResponsiveNavLink
                    @click="userStore.closeNavDropdown()"
                    :href="route('library')"
                    :active="route().current('library')">
                    My Library
                </JetResponsiveNavLink>

                <JetResponsiveNavLink
                    @click="userStore.closeNavDropdown()"
                    :href="route('stream')"
                    :active="route().current('stream')">
                        Stream
                </JetResponsiveNavLink>

                <JetResponsiveNavLink
                    @click="userStore.closeNavDropdown()"
                    :href="route('schedule')"
                    :active="route().current('schedule')">
                        Schedule
                </JetResponsiveNavLink>

                <JetResponsiveNavLink
                    v-if="$page.props.user.role_id === 2 || $page.props.user.role_id === 3 || $page.props.user.role_id === 4"
                    @click="userStore.closeNavDropdown()"
                    :href="route('news')"
                    :active="route().current('news')">
                        News
                </JetResponsiveNavLink>

                <JetResponsiveNavLink
                    v-if="$page.props.user.role_id === 3 || $page.props.user.role_id === 4"
                    @click="userStore.closeNavDropdown()"
                    :href="route('movies')"
                    :active="route().current('movies')">
                        Movies
                </JetResponsiveNavLink>

                <JetResponsiveNavLink
                    v-if="$page.props.user.role_id === 2 || $page.props.user.role_id === 3 || $page.props.user.role_id === 4"
                    @click="userStore.closeNavDropdown()"
                    :href="route('shows')"
                    :active="route().current('shows')">
                        Shows
                </JetResponsiveNavLink>

                <JetResponsiveNavLink
                    @click="userStore.closeNavDropdown()"
                    :href="route('shop')"
                    :active="route().current('shop')">
                        Shop
                </JetResponsiveNavLink>

                <JetResponsiveNavLink
                    @click="userStore.closeNavDropdown()"
                    :href="route('profile.show')"
                    :active="route().current('profile.show')">
                        Settings
                </JetResponsiveNavLink>

                <JetResponsiveNavLink
                    v-if="$page.props.user.role_id === 4"
                    @click="userStore.closeNavDropdown()"
                    :href="route('training')"
                    :active="route().current('training')">
                        Training
                </JetResponsiveNavLink>

                <!-- Authentication -->
                <form method="POST" @submit.prevent="logout">
                    <JetResponsiveNavLink as="button" class="border-t-0">
                        Log Out
                    </JetResponsiveNavLink>
                </form>
                <div class="flex flex-col w-full space-y-1 text-gray-600 text-sm mb-6">
                    <div class="flex pt-12 justify-center">Web application concept and design</div>
                    <div class="flex justify-center">not&#174;TV &#169; 2010 - {{new Date().getFullYear()}}</div>
                    <div class="flex justify-center">notTV Beta v0.4</div>
                    <div class="flex pt-4 justify-center">Please send us comments and questions <a href="https://help.not.tv/" target="_blank" class="text-blue-600 hover:text-blue-40">&nbsp; here</a>.</div>
                </div>
            </div>
        </div> <div class="fixed w-full bottom-4 text-center hidden">Scroll the menu.</div>
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

const showingNavigationDropdown = ref(false);

let videoPlayerStore = useVideoPlayerStore();
let chatStore = useChatStore();
let userStore = useUserStore();

const logout = () => {
    videoPlayerStore.fullPage = true;
    videoPlayerStore.loggedIn = false;
    videoPlayerStore.class = "welcomeVideoClass";
    videoPlayerStore.videoContainerClass = "welcomeVideoContainer";
    chatStore.class = "chatHidden";
    chatStore.show = false;
    Inertia.post(route('logout'));
};

</script>

<style>
.hamburgerMask {
    z-index: 100;
}
</style>
