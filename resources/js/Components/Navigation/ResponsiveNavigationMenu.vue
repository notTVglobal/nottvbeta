<template>
    <!-- Hamburger -->
    <div class="absolute top-3 right-4 hamburgerMask">
        <div class="-mr-2 flex items-center lg:hidden z-50">
                <div class="-mt-16 mr-12">
                    <NotificationsButton class=""/>
                </div>
            <button
                class="inline-flex items-center justify-center p-2 rounded-md text-gray-100 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition"
                @click="showingNavigationDropdown = ! showingNavigationDropdown">
                <span class="pr-2">MENU</span>
                <svg
                    class="h-6 w-6"
                    stroke="currentColor"
                    fill="none"
                    viewBox="0 0 24 24"
                >
                    <path
                        :class="{'hidden': showingNavigationDropdown, 'inline-flex': ! showingNavigationDropdown }"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16"
                    />
                    <path
                        :class="{'hidden': ! showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
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
    <div :class="{'block': showingNavigationDropdown, 'hidden': ! showingNavigationDropdown}"
         class="lg:hidden bg-gray-800 text-white fixed top-12 w-full h-full">
        <!-- Responsive Settings Options -->
        <!--   Fix Menu height e.g., h-[calc(h-100%-16rem)]      -->
        <div class="pt-7 pb-4 h-5/6 overflow-y-auto">
            <div class="px-4 bg-gray-800 border-b border-spacing-4 border-1 border-white">

                <div class="flex justify-between">
                    <div class="flex justify-start pb-3">
                        <div v-if="$page.props.jetstream.managesProfilePhotos" class="w-10">
                            <Link @click="videoPlayer.makeVideoTopRight()"
                                  :href="route('profile.show')"
                                  :active="route().current('profile.show')">
                                <img class="h-10 w-10 rounded-full object-cover"
                                     :src="$page.props.user.profile_photo_url" :alt="$page.props.user.name">
                            </Link>
                        </div>
                        <div class="ml-3">
                            <div class="font-medium text-base text-gray-100">
                                <Link @click="videoPlayer.makeVideoTopRight()"
                                      :href="route('profile.show')"
                                      :active="route().current('profile.show')">
                                    {{ $page.props.user.name }}
                                </Link>
                            </div>
                            <div class="font-medium text-sm text-gray-100">
                                <Link @click="videoPlayer.makeVideoTopRight()"
                                      :href="route('profile.show')"
                                      :active="route().current('profile.show')">
                                    {{ $page.props.user.email }}
                                </Link>
                            </div>
                        </div>
                    </div>
                    <div class="justify-end text-right w-full">
                        <div v-if="$page.props.user.role_id === 2" class="text-xl text-fuchsia-700">PREMIUM SUBSCRIBER</div>
                        <div v-if="$page.props.user.role_id === 3" class="text-xl text-fuchsia-700">VIP</div>
                        <div v-if="$page.props.user.role_id === 4" class="text-xl text-fuchsia-700">CREATOR</div>
                        <div v-if="$page.props.user.isAdmin === 1" class="text-lg text-red-700">ADMIN</div>
                        <div v-if="$page.props.user.role_id === 1" class="align-self-end text-lg font-semibold text-fuchsia-700 hover:text-fuchsia-500">
                            <Link @click="videoPlayer.makeVideoTopRight()" :href="route('upgrade')" :active="route().current('upgrade')">
                                CLICK HERE TO UPGRADE YOUR ACCOUNT
                            </Link>
                        </div>
                    </div>

                </div>
            </div>


            <div class="pt-3 space-y-1 z-50 bg-gray-900">

                <JetResponsiveNavLink
                    v-if="$page.props.user.role_id === 4"
                    @click="videoPlayer.makeVideoTopRight()"
                    :href="route('dashboard')"
                    :active="route().current('dashboard')">
                    Dashboard
                </JetResponsiveNavLink>

                <JetResponsiveNavLink
                    @click="videoPlayer.makeVideoTopRight()"
                    :href="route('stream')"
                    :active="route().current('stream')">
                        Stream
                </JetResponsiveNavLink>

                <JetResponsiveNavLink
                    @click="videoPlayer.makeVideoTopRight()"
                    :href="route('schedule')"
                    :active="route().current('schedule')">
                        Schedule
                </JetResponsiveNavLink>

                <JetResponsiveNavLink
                    v-if="$page.props.user.role_id === 2 || $page.props.user.role_id === 3 || $page.props.user.role_id === 4"
                    @click="videoPlayer.makeVideoTopRight()"
                    :href="route('posts')"
                    :active="route().current('posts')">
                        Posts
                </JetResponsiveNavLink>

                <JetResponsiveNavLink
                    v-if="$page.props.user.role_id === 3 || $page.props.user.role_id === 4"
                    @click="videoPlayer.makeVideoTopRight()"
                    :href="route('channels')"
                    :active="route().current('channels')">
                        Channels
                </JetResponsiveNavLink>

                <JetResponsiveNavLink
                    v-if="$page.props.user.role_id === 3 || $page.props.user.role_id === 4"
                    @click="videoPlayer.makeVideoTopRight()"
                    :href="route('movies')"
                    :active="route().current('movies')">
                        Movies
                </JetResponsiveNavLink>

                <JetResponsiveNavLink
                    v-if="$page.props.user.role_id === 2 || $page.props.user.role_id === 3 || $page.props.user.role_id === 4"
                    @click="videoPlayer.makeVideoTopRight()"
                    :href="route('shows')"
                    :active="route().current('shows')">
                        Shows
                </JetResponsiveNavLink>

                <JetResponsiveNavLink
                    @click="videoPlayer.makeVideoTopRight()"
                    :href="route('shop')"
                    :active="route().current('shop')">
                        Shop
                </JetResponsiveNavLink>

                <JetResponsiveNavLink
                    @click="videoPlayer.makeVideoTopRight()"
                    :href="route('profile.show')"
                    :active="route().current('profile.show')">
                        Settings
                </JetResponsiveNavLink>

                <JetResponsiveNavLink
                    v-if="$page.props.user.role_id === 4"
                    @click="videoPlayer.makeVideoTopRight()"
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

            </div>
        </div> <div class="w-full absolute bottom-20 text-center">Scroll the menu.</div>
    </div>

</template>

<script setup>
import JetResponsiveNavLink from '@/Jetstream/ResponsiveNavLink.vue'
import NotificationsButton from '@/Components/Navigation/NotificationsButton.vue'
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useChatStore } from "@/Stores/ChatStore"
import { ref } from "vue"
import {Inertia} from "@inertiajs/inertia"

const showingNavigationDropdown = ref(false);

let videoPlayer = useVideoPlayerStore();
let chat = useChatStore();

const logout = () => {
    videoPlayer.fullPage = true;
    videoPlayer.loggedIn = false;
    videoPlayer.class = "videoBgFull";
    videoPlayer.videoContainerClass = "videoContainerBgFull";
    chat.class = "chatHidden";
    chat.show = false;
    Inertia.post(route('logout'));
};

</script>

<style>
.hamburgerMask {
    z-index: 100;
}
</style>
