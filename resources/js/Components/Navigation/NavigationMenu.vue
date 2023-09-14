<template>
    <div class="hidden lg:block fixed top-0 w-full nav-mask">
    <nav class="sticky top-0 bg-black border-b border-gray-100" :class="{ isFullPageCss: videoPlayerStore.fullPage }">
        <!-- Primary Navigation Menu -->
        <div class="mx-auto px-4 lg:px-6 xl:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center">
                        <Link @click="loadStreamPage()" :href="route('stream')">
                            <JetApplicationMark class="block h-9 w-auto"/>
                        </Link>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden space-x-8 lg:-my-px lg:ml-10 lg:flex">
                        <h3 class="inline-flex items-center relative">
                        <JetNavLink
                            v-touch="()=>(route('schedule'))"
                            @click="videoPlayerStore.makeVideoTopRight()"
                            :href="route('schedule')"
                            :active="route().current('schedule')">
                                Schedule
                        </JetNavLink>
                        </h3>
                        <h3 class="inline-flex items-center relative">
                        <JetNavLink
                            v-touch="()=>(route('news'))"
                            @click="videoPlayerStore.makeVideoTopRight()"
                            :href="route('news')"
                            :active="route().current('news')">
                                News
                        </JetNavLink>
                        </h3>
                        <h3 class="inline-flex items-center relative"
                            v-if="userStore.isSubscriber || userStore.isVip || userStore.isAdmin">
                        <JetNavLink
                            v-touch="()=>(route('movies'))"
                            @click="videoPlayerStore.makeVideoTopRight()"
                            :href="route('movies')"
                            :active="route().current('movies')">
                                Movies
                        </JetNavLink>
                        </h3>
                        <h3 class="inline-flex items-center relative"
                            v-if="userStore.isSubscriber || userStore.isVip || userStore.isAdmin">
                        <JetNavLink
                            v-touch="()=>(route('shows'))"
                            @click="videoPlayerStore.makeVideoTopRight()"
                            :href="route('shows')"
                            :active="route().current('shows')">
                                Shows
                        </JetNavLink>
                        </h3>
                        <h3 class="inline-flex items-center relative">
                        <JetNavLink
                            v-touch="()=>(route('shop'))"
                            @click="videoPlayerStore.makeVideoTopRight()"
                            :href="route('shop')"
                            :active="route().current('shop')">
                                Shop
                        </JetNavLink>
                        </h3>
                        <h3 class="inline-flex items-center relative"
                            v-if="userStore.isVip || userStore.isAdmin">
                        <JetNavLink
                            v-touch="()=>(route('library'))"
                            @click="videoPlayerStore.makeVideoTopRight()"
                            :href="route('library')"
                            :active="route().current('library')">
                                My Library
                            <div class="text-xs text-white bg-yellow-800 uppercase flex justify-center items-center ml-1 -right-4 top-1.5
                                    font-semibold inline-block py-0.5 px-1 rounded last:mr-0 mr-1">
                                coming soon
                            </div>
                        </JetNavLink>
                        </h3>
                    </div>

                </div>


                <div class="ml-20 lg:flex lg:items-center">

                    <div class="grid grid-cols-2 gap-4">
                        <div v-if="userStore.isCreator" class="text-yellow-600 uppercase hidden md:block text-xs w-full">
                            GOAL <span class="text-sm font-semibold">100</span> subscribers
                        </div>
                        <div class="align-text-top -mt-5 hidden">
                            <NotificationsButton class=""/>
                        </div>
                        <div>
                            <div v-if="!userStore.isAdmin && !userStore.isVip && !userStore.isSubscriber">
                                <JetNavLink v-touch="()=>(route('upgrade'))"
                                            @click="videoPlayerStore.makeVideoTopRight()"
                                            :href="route('upgrade')"
                                            :active="route().current('upgrade')"
                                            class="active:border-none hover:border-none focus:border-none border-none">
                                    <div class="w-full rounded-lg p-2 bg-gray-100 text-black hover:bg-gray-300 hover:text-green-900">UPGRADE NOW</div>
                                </JetNavLink>
                            </div>
                                <div v-if="userStore.isSubscriber && !userStore.isAdmin" class="text-fuchsia-700">PREMIUM</div>
                                <div v-if="userStore.isVip && !userStore.isAdmin" class="text-fuchsia-700">VIP</div>
                                <div v-if="userStore.isCreator && userStore.isSubscriber && !userStore.isAdmin" class="text-fuchsia-700">CREATOR</div>
                            <div v-if="userStore.isAdmin" class="text-red-700">ADMIN</div>
                        </div>
                    </div>
                    <!-- Settings Dropdown -->
                    <div class="relative menuMask">

                        <JetDropdown align="right" width="48">
                            <template #trigger>
                                <button v-if="$page.props.jetstream.managesProfilePhotos"
                                        class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                    <img class="h-8 w-8 rounded-full object-cover"
                                         :src="$page.props.user.profile_photo_url" :alt="$page.props.user.name">
                                </button>

                                <span v-else class="inline-flex rounded-md">
                                            <button type="button"
                                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">
                                                {{ $page.props.user.name }}

                                                <svg
                                                    class="ml-2 -mr-0.5 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path fill-rule="evenodd"
                                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                          clip-rule="evenodd"/>
                                                </svg>
                                            </button>
                                        </span>
                            </template>

                            <template #content>
                                <div class="divide-y">

                                    <div class="text-xs text-gray-800 py-2 ml-4">Hello, {{ $page.props.user.name }}</div>

                                    <div class="pt-2 pb-3">

                                        <JetDropdownLink
                                            v-if="userStore.isCreator"
                                            @click="videoPlayerStore.makeVideoTopRight()"
                                            :href="route('dashboard')">
                                            Dashboard
                                        </JetDropdownLink>

                                        <JetDropdownLink
                                            v-if="userStore.isNewsPerson"
                                            @click="videoPlayerStore.makeVideoTopRight()"
                                            :href="route('newsroom')">
                                            Newsroom
                                        </JetDropdownLink>

                                        <JetDropdownLink
                                            @click="videoPlayerStore.makeVideoTopRight()"
                                            :href="route('profile.show')">
                                            Settings
                                        </JetDropdownLink>

                                        <JetDropdownLink
                                            v-if="userStore.hasAccount"
                                            @click="billingPortal">
                                            Account
                                        </JetDropdownLink>

                                        <JetDropdownLink
                                            v-if="userStore.isCreator"
                                            @click="videoPlayerStore.makeVideoTopRight()"
                                            :href="route('training')">
                                            Training
                                        </JetDropdownLink>

                                        <JetDropdownLink
                                            v-if="userStore.isCreator"
                                            @click="videoPlayerStore.makeVideoTopRight()"
                                            :href="route('videoupload')">
                                            Video Upload
                                        </JetDropdownLink>

<!--                                        &lt;!&ndash; Creator Only Links &ndash;&gt;-->
<!--                                        <div v-if="userStore.isCreator">-->
<!--                                            <div class="border-t border-1 mt-3 border-gray-300 block px-4 py-2 text-xs text-gray-400">-->
<!--                                                Creator Only Links-->
<!--                                            </div>-->

<!--                                        </div>-->

                                        <!-- Admin Only Links -->
                                        <div v-if="userStore.isAdmin">
                                            <div class="border-t border-1 mt-3 border-gray-300 block px-4 py-2 text-xs text-gray-400">
                                                Admin Only Links
                                            </div>

                                            <JetDropdownLink
                                                @click="videoPlayerStore.makeVideoTopRight()"
                                                :href="route('admin.settings')">
                                                Admin Settings
                                            </JetDropdownLink>

                                            <JetDropdownLink
                                                @click="videoPlayerStore.makeVideoTopRight()"
                                                :href="route('admin.channels')">
                                                Channels
                                            </JetDropdownLink>

                                            <JetDropdownLink
                                                @click="videoPlayerStore.makeVideoTopRight()"
                                                :href="route('calculations')">
                                                Calculations
                                            </JetDropdownLink>

                                            <JetDropdownLink
                                                @click="videoPlayerStore.makeVideoTopRight()"
                                                :href="route('mistServerApi')">
                                                MistServer API
                                            </JetDropdownLink>

                                        </div>
                                        <!-- Authentication -->
                                        <form @submit.prevent="logout">

                                            <JetDropdownLink as="button">
                                                Log Out
                                            </JetDropdownLink>
                                        </form>

                                    </div>
                                    <div class="border-t border-gray-100">

                                    </div>

                                    <div class="grid grid-col-1 w-full text-gray-400 text-sm py-2">
                                        <AppVersion />
                                    </div>

                                </div>
                            </template>
                        </JetDropdown>
                    </div>
                </div>



            </div>
        </div>
    </nav>
    </div>
</template>

<script setup>
import { computed } from "vue"
import JetApplicationMark from '@/Jetstream/ApplicationMark'
import JetDropdownLink from '@/Jetstream/DropdownLink'
import { Link } from '@inertiajs/inertia-vue3'
import NotificationsButton from "@/Components/Navigation/NotificationsButton"
import JetDropdown from '@/Jetstream/Dropdown'
import JetNavLink from '@/Jetstream/NavLink'
import {Inertia} from "@inertiajs/inertia"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useChatStore } from "@/Stores/ChatStore"
import { useStreamStore } from "@/Stores/StreamStore"
import { useUserStore } from "@/Stores/UserStore"
import { useWelcomeStore } from "@/Stores/WelcomeStore"
import AppVersion from "@/Components/AppVersion/AppVersion.vue";


let chat = useChatStore();
let videoPlayerStore = useVideoPlayerStore();
let streamStore = useStreamStore()
let userStore = useUserStore()
let welcomeStore = useWelcomeStore()

let props = defineProps({
    user: Object,
})

function loadStreamPage() {
    videoPlayerStore.makeVideoFullPage()
    videoPlayerStore.ott = 0
    userStore.showNavDropdown = false
}

const logout = () => {
    Inertia.post(route('logout'));
    videoPlayerStore.mute();
    videoPlayerStore.fullPage = true;
    videoPlayerStore.loggedIn = false;
    videoPlayerStore.ottChat = false;
    videoPlayerStore.ott = 0;
    videoPlayerStore.class = "videoBgFull";
    videoPlayerStore.videoContainerClass = "videoContainerBgFull";
    userStore.showNavDropdown = false;
    userStore.clearUserData()
};

// let isStreamPage = false
//
// function setPage() {
//     if (videoPlayerStore.currentPage = "stream") {
//         videoPlayerStore.currentPageIsStream = true;
//     } else
//         videoPlayerStore.currentPageIsStream = false;
// }

// setPage()

function billingPortal() {
    location.href = ('https://not.tv/billing-portal')
}

</script>
<style>

.isFullPageCss {
    background: rgba(0, 0, 0, 0.8);
    /*background: yellow;*/
}

</style>
