<template>
    <!-- Hamburger -->
    <div class="-mr-2 flex items-center sm:hidden z-50">
        <button
            class="inline-flex items-center justify-center p-2 rounded-md text-gray-100 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition"
            @click="showingNavigationDropdown = ! showingNavigationDropdown">
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
    <!-- Responsive Navigation Menu -->
    <div :class="{'block': showingNavigationDropdown, 'hidden': ! showingNavigationDropdown}"
         class="sm:hidden bg-gray-800 text-gray-50 mb-5">
        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-4 border-t border-gray-200">
            <div class="flex items-center px-4">
                <div v-if="$page.props.jetstream.managesProfilePhotos" class="shrink-0 mr-3">
                    <img class="h-10 w-10 rounded-full object-cover"
                         :src="$page.props.user.profile_photo_url" :alt="$page.props.user.name">
                </div>

                <div>
                    <div class="font-medium text-base text-gray-100">
                        {{ $page.props.user.name }}
                    </div>
                    <div class="font-medium text-sm text-gray-100">
                        {{ $page.props.user.email }}
                    </div>
                </div>
            </div>

            <div class="mt-3 space-y-1 z-50">

                <JetResponsiveNavLink @click="videoPlayer.makeVideoTopRight()"
                                      :href="route('dashboard')"
                                      :active="route().current('dashboard')">
                    Dashboard
                </JetResponsiveNavLink>
                <JetResponsiveNavLink @click="videoPlayer.makeVideoTopRight()"
                                      :href="route('stream')"
                                      :active="route().current('stream')"

                >
                    Stream
                </JetResponsiveNavLink>
                <JetResponsiveNavLink @click="videoPlayer.makeVideoFullPage()"
                                      :href="route('image')"
                                      :active="route().current('image')"

                >
                    Image Uploader
                </JetResponsiveNavLink>
                <JetResponsiveNavLink @click="videoPlayer.makeVideoTopRight()"
                                      :href="route('video')"
                                      :active="route().current('video')"

                >
                    Video
                </JetResponsiveNavLink>
                <JetResponsiveNavLink @click="videoPlayer.makeVideoTopRight()"
                                      :href="route('shows')"
                                      :active="route().current('shows')"

                >
                    Shows
                </JetResponsiveNavLink>

                <JetResponsiveNavLink @click="videoPlayer.makeVideoTopRight()"
                                      :href="route('training')"
                                      :active="route().current('training')">
                    Training
                </JetResponsiveNavLink>

                <JetResponsiveNavLink @click="videoPlayer.makeVideoTopRight()"
                                      :href="route('profile.show')"
                                      :active="route().current('profile.show')">
                    Profile
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
import JetResponsiveNavLink from '@/Jetstream/ResponsiveNavLink.vue';
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js";
import { ref } from "vue";
import {Inertia} from "@inertiajs/inertia";

const showingNavigationDropdown = ref(false);

let videoPlayer = useVideoPlayerStore();

const logout = () => {
    videoPlayer.fullPage = true;
    videoPlayer.loggedIn = false;
    videoPlayer.class = "videoBgFull";
    videoPlayer.videoContainerClass = "videoContainerBgFull";
    Inertia.post(route('logout'));
};

</script>
