<template>
    <!-- Hamburger -->
    <div class="absolute top-3 right-4 hamburgerMask">
        <div class="-mr-2 flex items-center lg:hidden z-50">
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
        <div class="pt-7 pb-4">
            <div class="flex items-center px-4 bg-gray-800 pb-3 border-b border-spacing-4 border-1 border-white">
                <div v-if="$page.props.jetstream.managesProfilePhotos" class="shrink-0 mr-3">
                    <Link @click="videoPlayer.makeVideoTopRight()"
                          :href="route('profile.show')"
                          :active="route().current('profile.show')">
                        <img class="h-10 w-10 rounded-full object-cover"
                             :src="$page.props.user.profile_photo_url" :alt="$page.props.user.name">
                    </Link>

                </div>

                <div>
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



            <div class="pt-3 space-y-1 z-50 bg-gray-900 h-max overflow-y-scroll">

<!--                <JetResponsiveNavLink-->
<!--                    @click="videoPlayer.makeVideoTopRight()"-->
<!--                    :href="route('video')"-->
<!--                    :active="route().current('video')">-->
<!--                    MistServer API-->
<!--                </JetResponsiveNavLink>-->
<!--                <JetResponsiveNavLink @click="videoPlayer.makeVideoFullPage()"-->
<!--                                      :href="route('image')"-->
<!--                                      :active="route().current('image')"-->

<!--                >-->
<!--                    Image Uploader-->
<!--                </JetResponsiveNavLink>-->

<!--                <JetResponsiveNavLink-->
<!--                    @click="videoPlayer.makeVideoTopRight()"-->
<!--                    :href="route('admin.channels.index')"-->
<!--                    :active="route().current('admin.channels.index')">-->
<!--                    Channels-->
<!--                </JetResponsiveNavLink>-->

<!--                <JetResponsiveNavLink-->
<!--                    @click="videoPlayer.makeVideoTopRight()"-->
<!--                    :href="route('teams.index')"-->
<!--                    :active="route().current('teams.index')">-->
<!--                    Teams-->
<!--                </JetResponsiveNavLink>-->

<!--                <JetResponsiveNavLink-->
<!--                    @click="videoPlayer.makeVideoTopRight()"-->
<!--                    :href="route('admin.users.index')"-->
<!--                    :active="route().current('admin.users.index')">-->
<!--                    Users-->
<!--                </JetResponsiveNavLink>-->

<!--                <div class="block px-4 py-2 text-xs text-gray-400">-->
<!--                    Manage Account-->
<!--                </div>-->

                <JetResponsiveNavLink @click="videoPlayer.makeVideoTopRight()"
                                      :href="route('dashboard')"
                                      :active="route().current('dashboard')">
                    Dashboard
                </JetResponsiveNavLink>
                <JetResponsiveNavLink @click="videoPlayer.makeVideoTopRight()"
                                      :href="route('profile.show')"
                                      :active="route().current('profile.show')">
                    Settings
                </JetResponsiveNavLink>

                <JetResponsiveNavLink @click="videoPlayer.makeVideoTopRight()"
                                      :href="route('training')"
                                      :active="route().current('training')">
                    Training
                </JetResponsiveNavLink>

<!--                <div class="block px-4 py-2 text-xs text-gray-400">-->
<!--                    Links-->
<!--                </div>-->

                <JetResponsiveNavLink @click="videoPlayer.makeVideoTopRight()"
                                      :href="route('stream')"
                                      :active="route().current('stream')"

                >
                    Stream
                </JetResponsiveNavLink>

                <JetResponsiveNavLink @click="videoPlayer.makeVideoTopRight()"
                                      :href="route('shows')"
                                      :active="route().current('shows')"

                >
                    Posts
                </JetResponsiveNavLink>

                <JetResponsiveNavLink @click="videoPlayer.makeVideoTopRight()"
                                      :href="route('shows')"
                                      :active="route().current('shows')"

                >
                    Channels
                </JetResponsiveNavLink>

                <JetResponsiveNavLink @click="videoPlayer.makeVideoTopRight()"
                                      :href="route('shows')"
                                      :active="route().current('shows')"

                >
                    Movies
                </JetResponsiveNavLink>

                <JetResponsiveNavLink @click="videoPlayer.makeVideoTopRight()"
                                      :href="route('shows')"
                                      :active="route().current('shows')"

                >
                    Shows
                </JetResponsiveNavLink>

                <JetResponsiveNavLink @click="videoPlayer.makeVideoTopRight()"
                                      :href="route('shows')"
                                      :active="route().current('shows')"

                >
                    Shop
                </JetResponsiveNavLink>

                <JetResponsiveNavLink @click="videoPlayer.makeVideoTopRight()"
                                      :href="route('shows')"
                                      :active="route().current('shows')"

                >
                    Schedule
                </JetResponsiveNavLink>



                <!-- Authentication -->
                <form method="POST" @submit.prevent="logout">
                    <JetResponsiveNavLink as="button">
                        Log Out
                    </JetResponsiveNavLink>
                </form>

            </div>
        </div>
    </div>
</template>

<script setup>
import JetResponsiveNavLink from '@/Jetstream/ResponsiveNavLink.vue'
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
