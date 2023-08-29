<template>
    <nav class="sticky top-0 bg-black border-b border-gray-100 z-50" :class="{ isFullPageCss: videoPlayerStore.fullPage }">
        <!-- Primary Navigation Menu -->
        <div class="max-w-7xl mx-auto px-4 lg:px-6 xl:px-8 z-50">
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
                            v-touch="()=>(route('stream'))"
                            @click="videoPlayerStore.makeVideoFullPage()"
                            :href="route('stream')"
                            :active="route().current('stream')">
                                Stream
<!--                            <div v-if="streamStore.isLive"-->
<!--                                class="text-xs text-white bg-red-800 uppercase flex justify-center items-center absolute -right-4 top-1.5-->
<!--                                    font-semibold inline-block py-0.5 px-1 rounded last:mr-0 mr-1">-->
<!--                               live-->
<!--                            </div>-->
                        </JetNavLink>
                        </h3>
                        <h3 class="inline-flex items-center relative">
                        <JetNavLink
                            v-touch="()=>(route('schedule'))"
                            @click="videoPlayerStore.makeVideoTopRight()"
                            :href="route('schedule')"
                            :active="route().current('schedule')">
                                Schedule
                        </JetNavLink>
                        </h3>
                        <h3 class="inline-flex items-center relative"
                            v-if="$page.props.user.role_id === 2 || $page.props.user.role_id === 3 || $page.props.user.role_id === 4">
                        <JetNavLink
                            v-touch="()=>(route('news'))"
                            @click="videoPlayerStore.makeVideoTopRight()"
                            :href="route('news')"
                            :active="route().current('news')">
                                News
                        </JetNavLink>
                        </h3>
                        <h3 class="inline-flex items-center relative"
                            v-if="$page.props.user.role_id === 3 || $page.props.user.role_id === 4">
                        <JetNavLink
                            v-touch="()=>(route('movies'))"
                            @click="videoPlayerStore.makeVideoTopRight()"
                            :href="route('movies')"
                            :active="route().current('movies')">
                                Movies
                        </JetNavLink>
                        </h3>
                        <h3 class="inline-flex items-center relative"
                            v-if="$page.props.user.role_id === 2 || $page.props.user.role_id === 3 || $page.props.user.role_id === 4">
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
                            v-if="$page.props.user.role_id >= 3">
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
                        <div v-if="$page.props.user.role_id === 4" class="text-yellow-600 uppercase hidden md:block mt-5 text-xs w-full">
                            GOAL <span class="text-sm font-semibold">100</span> subscribers
                        </div>
                    </div>

                </div>


                <div class="hidden lg:flex lg:items-center lg:ml-6 z-50">

                    <div class="grid grid-cols-2 gap-4">
                        <div class="align-text-top -mt-5">
                            <NotificationsButton class=""/>
                        </div>
                        <div>
                            <div v-if="$page.props.user.role_id === 1">
                                <JetNavLink v-touch="()=>(route('upgrade'))" @click="videoPlayerStore.makeVideoTopRight()" :href="route('upgrade')" :active="route().current('upgrade')" class="active:border-none hover:border-none focus:border-none border-none">
                                    <div class="rounded-lg p-2 bg-gray-100 text-black hover:bg-gray-300 hover:text-green-900">CLICK HERE TO UPGRADE YOUR ACCOUNT</div>
                                </JetNavLink>
                            </div>
                            <div v-if="$page.props.user.role_id === 2" class="text-fuchsia-700">PREMIUM SUBSCRIBER</div>
                            <div v-if="$page.props.user.role_id === 3" class="text-fuchsia-700">VIP</div>
                            <div v-if="$page.props.user.role_id === 4" class="text-fuchsia-700">CREATOR</div>
                            <div v-if="$page.props.user.isAdmin === 1" class="text-sm text-red-700">ADMIN</div>
                        </div>
                    </div>
                    <!-- Settings Dropdown -->
                    <div class="ml-3 relative z-50">

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
                                            v-if="$page.props.user.role_id === 4"
                                            @click="videoPlayerStore.makeVideoTopRight()"
                                            :href="route('dashboard')">
                                            Dashboard
                                        </JetDropdownLink>

                                        <JetDropdownLink
                                            @click="videoPlayerStore.makeVideoTopRight()"
                                            :href="route('profile.show')">
                                            Settings
                                        </JetDropdownLink>

                                        <JetDropdownLink
                                            v-if="$page.props.user.role_id >= 2"
                                            @click="billingPortal">
                                            Account
                                        </JetDropdownLink>

                                        <JetDropdownLink
                                            v-if="$page.props.user.role_id === 4"
                                            @click="videoPlayerStore.makeVideoTopRight()"
                                            :href="route('training')">
                                            Training
                                        </JetDropdownLink>

                                        <JetDropdownLink
                                            v-if="$page.props.user.role_id === 4"
                                            @click="videoPlayerStore.makeVideoTopRight()"
                                            :href="route('videoupload')">
                                            Video Upload
                                        </JetDropdownLink>

<!--                                        &lt;!&ndash; Creator Only Links &ndash;&gt;-->
<!--                                        <div v-if="$page.props.user.role_id === 4">-->
<!--                                            <div class="border-t border-1 mt-3 border-gray-300 block px-4 py-2 text-xs text-gray-400">-->
<!--                                                Creator Only Links-->
<!--                                            </div>-->

<!--                                        </div>-->

                                        <!-- Admin Only Links -->
                                        <div v-if="$page.props.user.isAdmin === 1">
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
                                                :href="route('calculations')">
                                                Calculations
                                            </JetDropdownLink>

                                            <JetDropdownLink
                                                @click="videoPlayerStore.makeVideoTopRight()"
                                                :href="route('video')">
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
<!--                                        <div class="flex pt-6 justify-center ">Web application</div>-->
<!--                                        <div class="flex justify-center">concept and design</div>-->
                                        <div class="flex justify-center">not<span class="text-xs">&#174;</span>TV &#169;2010-{{new Date().getFullYear()}}</div>
                                        <div class="flex justify-center">Beta Preview v0.7</div>
<!--                                        <div class="flex justify-center">Please send us</div>-->
<!--                                        <div class="flex justify-center">comments and questions</div>-->
<!--                                        <div class="flex justify-center"><a href="https://help.not.tv/" target="_blank" class="text-blue-600 hover:text-blue-40">here</a>.</div>-->
                                    </div>

                                </div>
                            </template>
                        </JetDropdown>
                    </div>
                </div>



            </div>
        </div>
    </nav>
</template>

<script setup>
import JetApplicationMark from '@/Jetstream/ApplicationMark.vue';
import JetDropdownLink from '@/Jetstream/DropdownLink.vue';
import { Link } from '@inertiajs/inertia-vue3';
import NotificationsButton from "@/Components/Navigation/NotificationsButton.vue";
import JetDropdown from '@/Jetstream/Dropdown.vue';
import JetNavLink from '@/Jetstream/NavLink.vue';
import {Inertia} from "@inertiajs/inertia";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js";
import { useChatStore } from "@/Stores/ChatStore.js";
import { useStreamStore } from "@/Stores/StreamStore";
import { useUserStore } from "@/Stores/UserStore";
import { useWelcomeStore } from "@/Stores/WelcomeStore";

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
    videoPlayerStore.ottClass = 'OttClose'
    userStore.showNavDropdown = false
}

const logout = () => {
    // videoPlayerStore.changeChannel('one');
    videoPlayerStore.mute();
    videoPlayerStore.fullPage = true;
    videoPlayerStore.loggedIn = false;
    videoPlayerStore.class = "videoBgFull";
    videoPlayerStore.videoContainerClass = "videoContainerBgFull";
    chat.class = "chatHidden";
    chat.showChat = false;
    Inertia.post(route('logout'));
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
    userStore.closeNavDropdown()
    axios.get('billing-portal-access').then(function (response) {
        console.log(response);
        window.location = response.data
    })
}

</script>
<style>

.isFullPageCss {
    background: rgba(0, 0, 0, 0.8);
    /*background: yellow;*/
}

</style>
